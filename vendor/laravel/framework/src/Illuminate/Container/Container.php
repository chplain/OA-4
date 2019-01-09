<?php

namespace Illuminate\Container;

use ArrayAccess;//数组式访问接口
use Closure;//匿名函数类
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\Container as ContainerContract; //container契约
use Illuminate\Support\Arr;
use LogicException;
use ReflectionClass;//类的反射
use ReflectionParameter;// 反射函数或参数的信息

/**
 * Class Container
 * container 的两个主要的作用:绑定->解析
 * 扩展:
 *     $container->extend(APIClient::class, function ($client, Container $container) {
 *            return new APIClientDecorator($client);
 *           }); //封装一个类返回不同的对象.如:APIClient::class,APIClientDecorator,两个类要实现相同的接口
 *    //
 *      $container->bind(Getable::class, APIClient::class);
 *
 *    // 此时 $instance 的 $client 属性已经是 APIClentDecorator 类型了
 *       $instance = $container->make(User::class);
 * 绑定:
 *    绑定单例:
 *         singleton
 *
 *                $container->singleton(Cache::class, RedisCache::class);
 *           单例绑定闭包:
 *                $container->singleton(Database::class, function (Container $container) {
 *                  return new MySQLDatabase('localhost', 'testdb', 'user', 'pass');
 *                });
 *            绑定具体类:
 *                $container->singleton(MySQLDatabase::class);
 *         绑定实例: instance
 *             重复使用已有的实例，用 instance()方法
 *              $container->instance(Container::class, $container);//Laravel就是用这种方法来确保每次获取到的都是同一个 Container实例
 *                   Container 还可以用来保存任何值，例如 configuration 数据：
 *                     $container->instance('database.name', 'testdb');
 *                      $db_name = $container->make('database.name');
 *                      数组访问:
 *                         $container['database.name'] = 'testdb';
 *                         $db_name = $container['database.name'];
 *                      数组访问语法还可以代替 make() 来实例化对象：
 *                         $db = $container['database'];
 *    bind(每次解析时都会新实例化一个对象(或重新调用闭包)):
 *         绑定接口到实现: bind(xxInterface::class, xxClass::class) //xxClass为xxInterface的实现
 *         绑定到抽象类和具体类:
 *                抽象类:bind(MyAbstract::class,MyConcreteClass::class)
 *                具体类:bind(MyDatabase::class,CustomMysqlDatabase::class) CustomMysql继承与MyDatabase
 *         自定义绑定:
 *             将bind的第二个参数换为Closure bind(database:class,function(Container $container){})
 *         绑定任意名称(只能用 make() 来获取实例):
 *             $container->bind('database', MySQLDatabase::class);
 *             $db = $container->make('database');
 *
 *         绑定初始数据
 *         情景绑定
 *         tag绑定tag()://将实例关系到标记
 *              $container->tag(MyPlugin::class, 'plugin');
 *              $container->tag(AnotherPlugin::class, 'plugin');
 *           以数组的形式取回所有「标记」的实例：
 *             foreach ($container->tagged('plugin') as $plugin) {
 *                     $plugin->init();
 *               }
 *             tag() 方法的两个参数都可以接受数组：
 *                  $container->tag([MyPlugin::class, AnotherPlugin::class], 'plugin');
 *                  $container->tag(MyPlugin::class, ['plugin', 'plugin.admin']);
 *
 *
 *
 *       bindMethod()方法绑定:
 *
 *       上下文绑定
 *
 *
 * 解析:
 *   make:
 * class MyClass
 *  {
 *    private $dependency;
 *    public function __construct(AnotherClass $dependency)
 *   {
 *    $this->dependency = $dependency;
 *   }
 * }
 *    $instance = $container->make(MyClass::class);
 *    Container 会自动实例化依赖的对象，所以它等同于：
           $instance = new MyClass(new AnotherClass());
 *
 * 回调:
 *    使用resolving()注册callback(回调函数),在绑定的类解析后调用,不用重新绑定
 *    $container->resolving(Logger::class, function (Logger $logger) { //logger 解析后调用
 *       $logger->setLevel('debug');
 *     });
 * @package Illuminate\Container
 */
class Container implements ArrayAccess, ContainerContract
{
    /**
     * The current globally available container (if any).
     * 当前全局可用的容器(如果有)
     * @var static
     */
    protected static $instance;

    /**
     * An array of the types that have been resolved.
     * 已解析的类型数组。
     * @var array
     */
    protected $resolved = [];

    /**
     * The container's bindings.
     * 注册类的别名的集合
     * @var array
     */
    protected $bindings = [];

    /**
     * The container's method bindings.
     *
     * @var array
     */
    protected $methodBindings = [];

    /**
     * The container's shared instances.
     *  容器的共享实例
     * @var array
     */
    protected $instances = [];

    /**
     * The registered type aliases.
     *  已经注册的类型别名
     * @var array
     */
    protected $aliases = [];

    /**
     * The registered aliases keyed by the abstract name.
     *  已经注册的抽象类的别名
     * @var array
     */
    protected $abstractAliases = [];

    /**
     * The extension closures for services.
     * service 的扩展闭包
     * @var array
     */
    protected $extenders = [];

    /**
     * All of the registered tags.
     * 已经注册的tags
     * @var array
     */
    protected $tags = [];

    /**
     * The stack of concretions currently being built.
     *  包含正在构建的栈
     * @var array
     */
    protected $buildStack = [];

    /**
     * The parameter override stack.
     *  参数覆盖堆栈。
     * @var array
     */
    protected $with = [];

    /**
     * The contextual binding map.
     *
     * @var array
     */
    public $contextual = [];

    /**
     * All of the registered rebound callbacks.
     *
     * @var array
     */
    protected $reboundCallbacks = [];

    /**
     * All of the global resolving callbacks.
     *
     * @var array
     */
    protected $globalResolvingCallbacks = [];

    /**
     * All of the global after resolving callbacks.
     *
     * @var array
     */
    protected $globalAfterResolvingCallbacks = [];

    /**
     * All of the resolving callbacks by class type.
     *
     * @var array
     */
    protected $resolvingCallbacks = [];

    /**
     * All of the after resolving callbacks by class type.
     *
     * @var array
     */
    protected $afterResolvingCallbacks = [];

    /**
     * Define a contextual binding.
     *
     * @param  array|string $concrete
     * @return \Illuminate\Contracts\Container\ContextualBindingBuilder
     */
    public function when($concrete)
    {
        $aliases = [];

        foreach (Arr::wrap($concrete) as $c) {
            $aliases[] = $this->getAlias($c);
        }

        return new ContextualBindingBuilder($this, $aliases);
    }

    /**
     * Determine if the given abstract type has been bound.
     * 确定是否已绑定给定的抽象类型。
     * @param  string $abstract
     * @return bool
     */
    public function bound($abstract)
    {
        return isset($this->bindings[$abstract]) ||
            isset($this->instances[$abstract]) ||
            $this->isAlias($abstract);
    }

    /**
     * 查找指定的id是否已经被绑定
     *  {@inheritdoc}
     */
    public function has($id)
    {
        return $this->bound($id);
    }

    /**
     * Determine if the given abstract type has been resolved.
     * 确定给定的抽象类型是否已经被回调函数调用
     * @param  string $abstract
     * @return bool
     */
    public function resolved($abstract)
    {
        if ($this->isAlias($abstract)) {
            $abstract = $this->getAlias($abstract);
        }

        return isset($this->resolved[$abstract]) ||
            isset($this->instances[$abstract]);
    }

    /**
     * Determine if a given type is shared.
     * 确定给定的类型是否被共享
     * @param  string $abstract
     * @return bool
     */
    public function isShared($abstract)
    {
        return isset($this->instances[$abstract]) ||
            (isset($this->bindings[$abstract]['shared']) &&
                $this->bindings[$abstract]['shared'] === true);
    }

    /**
     * Determine if a given string is an alias.
     * 确定给定的字符串是否是别名
     * @param  string $name
     * @return bool
     */
    public function isAlias($name)
    {
        return isset($this->aliases[$name]);
    }


    /**
     * Register a binding with the container.
     * 绑定
     * @param string $abstract
     * @param null $concrete
     * @param bool $shared
     * @throws BindingResolutionException
     * @throws \ReflectionException
     */
    public function bind($abstract, $concrete = null, $shared = false)
    {
        $this->dropStaleInstances($abstract);

        // If no concrete type was given, we will simply set the concrete type to the
        // abstract type. After that, the concrete type to be registered as shared
        // without being forced to state their classes in both of the parameters.
        // 如果传入的实现为空，则绑定 $concrete 自己
        if (is_null($concrete)) {
            $concrete = $abstract;
        }

        // If the factory is not a Closure, it means it is just a class name which is
        // bound into this container to the abstract type and we will just wrap it
        // up inside its own Closure to give us more convenience when extending.
        // 目的是将 $concrete 转成闭包函数
        if (!$concrete instanceof Closure) {
            $concrete = $this->getClosure($abstract, $concrete);
        }

        // 存储到 $bindings 数组中，如果 $shared = true, 则表示绑定单例
        $this->bindings[$abstract] = compact('concrete', 'shared');

        // If the abstract type was already resolved in this container we'll fire the
        // rebound listener so that any objects which have already gotten resolved
        // can have their copy of the object updated via the listener callbacks.
        if ($this->resolved($abstract)) {
            $this->rebound($abstract);
        }
    }

    /**
     * Get the Closure to be used when building a type.
     *
     * @param  string $abstract
     * @param  string $concrete
     * @return \Closure
     */
    protected function getClosure($abstract, $concrete)
    {
        return function ($container, $parameters = []) use ($abstract, $concrete) {
            if ($abstract == $concrete) {
                return $container->build($concrete);
            }

            return $container->make($concrete, $parameters);
        };
    }

    /**
     * Determine if the container has a method binding.
     *
     * @param  string $method
     * @return bool
     */
    public function hasMethodBinding($method)
    {
        return isset($this->methodBindings[$method]);
    }

    /**
     * Bind a callback to resolve with Container::call.
     *
     * @param  array|string $method
     * @param  \Closure $callback
     * @return void
     */
    public function bindMethod($method, $callback)
    {
        $this->methodBindings[$this->parseBindMethod($method)] = $callback;
    }

    /**
     * Get the method to be bound in class@method format.
     *
     * @param  array|string $method
     * @return string
     */
    protected function parseBindMethod($method)
    {
        if (is_array($method)) {
            return $method[0] . '@' . $method[1];
        }

        return $method;
    }

    /**
     * Get the method binding for the given method.
     *
     * @param  string $method
     * @param  mixed $instance
     * @return mixed
     */
    public function callMethodBinding($method, $instance)
    {
        return call_user_func($this->methodBindings[$method], $instance, $this);
    }

    /**
     * Add a contextual binding to the container.
     *
     * @param  string $concrete
     * @param  string $abstract
     * @param  \Closure|string $implementation
     * @return void
     */
    public function addContextualBinding($concrete, $abstract, $implementation)
    {
        $this->contextual[$concrete][$this->getAlias($abstract)] = $implementation;
    }

    /**
     * Register a binding if it hasn't already been registered.
     *
     * @param  string $abstract
     * @param  \Closure|string|null $concrete
     * @param  bool $shared
     * @return void
     */
    public function bindIf($abstract, $concrete = null, $shared = false)
    {
        if (!$this->bound($abstract)) {
            $this->bind($abstract, $concrete, $shared);
        }
    }

    /**
     * Register a shared binding in the container.
     * 绑定单例
     * @param  string $abstract
     * @param  \Closure|string|null $concrete
     * @return void
     */
    public function singleton($abstract, $concrete = null)
    {
        $this->bind($abstract, $concrete, true);
    }

    /**
     * "Extend" an abstract type in the container.
     *  在容器里扩展一个抽象的类
     * @param  string $abstract
     * @param  \Closure $closure
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    public function extend($abstract, Closure $closure)
    {
        $abstract = $this->getAlias($abstract);

        if (isset($this->instances[$abstract])) {
            $this->instances[$abstract] = $closure($this->instances[$abstract], $this);

            $this->rebound($abstract);
        } else {
            $this->extenders[$abstract][] = $closure;

            if ($this->resolved($abstract)) {
                $this->rebound($abstract);
            }
        }
    }

    /**
     * Register an existing instance as shared in the container.
     * 绑定实例
     * @param  string $abstract
     * @param  mixed $instance
     * @return mixed
     */
    public function instance($abstract, $instance)
    {
        $this->removeAbstractAlias($abstract);

        $isBound = $this->bound($abstract);

        unset($this->aliases[$abstract]);

        // We'll check to determine if this type has been bound before, and if it has
        // we will fire the rebound callbacks registered with the container and it
        // can be updated with consuming classes that have gotten resolved here.
        $this->instances[$abstract] = $instance;

        if ($isBound) {
            $this->rebound($abstract);
        }

        return $instance;
    }

    /**
     * Remove an alias from the contextual binding alias cache.
     *
     * @param  string $searched
     * @return void
     */
    protected function removeAbstractAlias($searched)
    {
        if (!isset($this->aliases[$searched])) {
            return;
        }

        foreach ($this->abstractAliases as $abstract => $aliases) {
            foreach ($aliases as $index => $alias) {
                if ($alias == $searched) {
                    unset($this->abstractAliases[$abstract][$index]);
                }
            }
        }
    }

    /**
     * Assign a set of tags to a given binding.
     * 绑定tag
     * @param  array|string $abstracts
     * @param  array|mixed ...$tags
     * @return void
     */
    public function tag($abstracts, $tags)
    {
        $tags = is_array($tags) ? $tags : array_slice(func_get_args(), 1);

        foreach ($tags as $tag) {
            if (!isset($this->tags[$tag])) {
                $this->tags[$tag] = [];
            }

            foreach ((array)$abstracts as $abstract) {
                $this->tags[$tag][] = $abstract;
            }
        }
    }

    /**
     * Resolve all of the bindings for a given tag.
     *
     * @param  string $tag
     * @return array
     */
    public function tagged($tag)
    {
        $results = [];
        //如果传入的 tag 标签值存在 tags 数组中，则遍历所有 $abstract,
        // 一一解析，将结果保存数组输出。
        if (isset($this->tags[$tag])) {
            foreach ($this->tags[$tag] as $abstract) {
                $results[] = $this->make($abstract);
            }
        }

        return $results;
    }

    /**
     * Alias a type to a different name.
     *
     * @param  string $abstract
     * @param  string $alias
     * @return void
     */
    public function alias($abstract, $alias)
    {
        $this->aliases[$alias] = $abstract;

        $this->abstractAliases[$abstract][] = $alias;
    }

    /**
     * Bind a new callback to an abstract's rebind event.
     *
     * @param  string $abstract
     * @param  \Closure $callback
     * @return mixed
     */
    public function rebinding($abstract, Closure $callback)
    {
        $this->reboundCallbacks[$abstract = $this->getAlias($abstract)][] = $callback;

        if ($this->bound($abstract)) {
            return $this->make($abstract);
        }
    }

    /**
     * Refresh an instance on the given target and method.
     *
     * @param  string $abstract
     * @param  mixed $target
     * @param  string $method
     * @return mixed
     */
    public function refresh($abstract, $target, $method)
    {
        return $this->rebinding($abstract, function ($app, $instance) use ($target, $method) {
            $target->{$method}($instance);
        });
    }


    /**
     *  Fire the "rebound" callbacks for the given abstract type.
     * 为给定的抽象类型触发“反弹”回调。
     * @param $abstract
     * @throws BindingResolutionException
     * @throws \ReflectionException
     */
    protected function rebound($abstract)
    {
        $instance = $this->make($abstract);

        foreach ($this->getReboundCallbacks($abstract) as $callback) {
            call_user_func($callback, $this, $instance);
        }
    }

    /**
     * Get the rebound callbacks for a given type.
     *获取给定类型的回调
     * @param  string $abstract
     * @return array
     */
    protected function getReboundCallbacks($abstract)
    {
        if (isset($this->reboundCallbacks[$abstract])) {
            return $this->reboundCallbacks[$abstract];
        }

        return [];
    }

    /**
     * Wrap the given closure such that its dependencies will be injected when executed.
     * 包装给定的闭包，以便在执行时注入其依赖项
     * @param  \Closure $callback
     * @param  array $parameters
     * @return \Closure
     */
    public function wrap(Closure $callback, array $parameters = [])
    {
        return function () use ($callback, $parameters) {
            return $this->call($callback, $parameters);
        };
    }

    /**
     * Call the given Closure / class@method and inject its dependencies.
     * 调用给定的Closure/class@method  注入其依赖项
     * @param  callable|string $callback
     * @param  array $parameters
     * @param  string|null $defaultMethod
     * @return mixed
     */
    public function call($callback, array $parameters = [], $defaultMethod = null)
    {
        return BoundMethod::call($this, $callback, $parameters, $defaultMethod);
    }

    /**
     * Get a closure to resolve the given type from the container.
     *
     * @param  string $abstract
     * @return \Closure
     */
    public function factory($abstract)
    {
        return function () use ($abstract) {
            return $this->make($abstract);
        };
    }


    /**
     * An alias function name for make().
     * @param $abstract
     * @param array $parameters
     * @return mixed|object|void
     * @throws BindingResolutionException
     * @throws \ReflectionException
     */
    public function makeWith($abstract, array $parameters = [])
    {
        return $this->make($abstract, $parameters);
    }


    /**
     * Resolve the given type from the container. 解析
     * @param string $abstract
     * @param array $parameters
     * @return mixed|object|void
     * @throws BindingResolutionException
     * @throws \ReflectionException
     */
    public function make($abstract, array $parameters = [])
    {
        return $this->resolve($abstract, $parameters);
    }

    /**
     *  {@inheritdoc}
     */
    public function get($id)
    {
        try {
            return $this->resolve($id);
        } catch (Exception $e) {
            if ($this->has($id)) {
                throw $e;
            }

            throw new EntryNotFoundException;
        }
    }


    /**
     * Resolve the given type from the container.
     *
     * @param $abstract
     * @param array $parameters
     * @return mixed|object|void
     * @throws BindingResolutionException
     * @throws \ReflectionException
     */
    protected function resolve($abstract, $parameters = [])
    {
        $abstract = $this->getAlias($abstract);

        //解析的对象是否有参数，如果有参数，还需要对参数做进一步的分析，
        //因为传入的参数，也可能是依赖注入的，所以还需要对传入的参数进行解析
        $needsContextualBuild = !empty($parameters) || !is_null(
                $this->getContextualConcrete($abstract)
            );

        // If an instance of the type is currently being managed as a singleton we'll
        // just return an existing instance instead of instantiating new instances
        // so the developer can keep using the same objects instance every time.
        //如果是绑定的单例，
        //并且不需要上面的参数依赖。我们就可以直接返回 $this->instances[$abstract]
        if (isset($this->instances[$abstract]) && !$needsContextualBuild) {
            return $this->instances[$abstract];
        }

        $this->with[] = $parameters;

        //从绑定的上下文找，是不是可以找到绑定类；如果没有，则再从 $bindings[] 中找关联的实现类；
        //最后还没有找到的话，就直接返回 $abstract 本身。
        $concrete = $this->getConcrete($abstract);

        // We're ready to instantiate an instance of the concrete type registered for
        // the binding. This will instantiate the types, as well as resolve any of
        // its "nested" dependencies recursively until all have gotten resolved.
        //如果之前找到的 $concrete 返回的是 $abstract 值，
        //或者 $concrete 是个闭包，则执行 $this->build($concrete)，
        //否则，表示存在嵌套依赖的情况，
        //则采用递归的方法执行 $this->make($concrete)，
        //直到所有的都解析完为止。
        if ($this->isBuildable($concrete, $abstract)) {
            $object = $this->build($concrete);
        } else {
            $object = $this->make($concrete);
        }

        // If we defined any extenders for this type, we'll need to spin through them
        // and apply them to the object being built. This allows for the extension
        // of services, such as changing configuration or decorating the object.
        //是否存在扩展，则相应扩展功能
        foreach ($this->getExtenders($abstract) as $extender) {
            $object = $extender($object, $this);
        }

        // If the requested type is registered as a singleton we'll want to cache off
        // the instances in "memory" so we can return it later without creating an
        // entirely new instance of an object on each subsequent request for it.
        //是绑定单例，则将解析的结果存到 $this->instances 数组中
        if ($this->isShared($abstract) && !$needsContextualBuild) {
            $this->instances[$abstract] = $object;
        }

        $this->fireResolvingCallbacks($abstract, $object);

        // Before returning, we will also set the resolved flag to "true" and pop off
        // the parameter overrides for this build. After those two things are done
        // we will be ready to return back the fully constructed class instance.
        //善后工作
        $this->resolved[$abstract] = true;

        array_pop($this->with);

        return $object;
    }

    /**
     * Get the concrete type for a given abstract.
     * 从绑定的上下文找，是不是可以找到绑定类；如果没有，则再从 $bindings[] 中找关联的实现类；
     * 最后还没有找到的话，就直接返回 $abstract 本身。
     * @param  string $abstract
     * @return mixed   $concrete
     */
    protected function getConcrete($abstract)
    {
        if (!is_null($concrete = $this->getContextualConcrete($abstract))) {
            return $concrete;
        }

        // If we don't have a registered resolver or concrete for the type, we'll just
        // assume each type is a concrete name and will attempt to resolve it as is
        // since the container should be able to resolve concretes automatically.
        if (isset($this->bindings[$abstract])) {
            return $this->bindings[$abstract]['concrete'];
        }

        return $abstract;
    }

    /**
     * Get the contextual concrete binding for the given abstract.
     *
     * @param  string $abstract
     * @return string|null
     */
    protected function getContextualConcrete($abstract)
    {
        if (!is_null($binding = $this->findInContextualBindings($abstract))) {
            return $binding;
        }

        // Next we need to see if a contextual binding might be bound under an alias of the
        // given abstract type. So, we will need to check if any aliases exist with this
        // type and then spin through them and check for contextual bindings on these.
        if (empty($this->abstractAliases[$abstract])) {
            return;
        }

        foreach ($this->abstractAliases[$abstract] as $alias) {
            if (!is_null($binding = $this->findInContextualBindings($alias))) {
                return $binding;
            }
        }
    }

    /**
     * Find the concrete binding for the given abstract in the contextual binding array.
     *
     * @param  string $abstract
     * @return string|null
     */
    protected function findInContextualBindings($abstract)
    {
        if (isset($this->contextual[end($this->buildStack)][$abstract])) {
            return $this->contextual[end($this->buildStack)][$abstract];
        }
    }

    /**
     * Determine if the given concrete is buildable.
     *
     * @param  mixed $concrete
     * @param  string $abstract
     * @return bool
     */
    protected function isBuildable($concrete, $abstract)
    {
        return $concrete === $abstract || $concrete instanceof Closure;
    }


    /**
     * Instantiate a concrete instance of the given type.
     * 方法分成两个分支：如果 $concrete instanceof Closure，则直接调用闭包函数，返回结果：$concrete()；
     * 另一种分支就是，传入的就是一个 $concrete === $abstract === 类名，通过反射方法，解析并 new 该类。
     * @param $concrete
     * @return mixed|object|void
     * @throws BindingResolutionException
     * @throws \ReflectionException
     */
    public function build($concrete)
    {
        // If the concrete type is actually a Closure, we will just execute it and
        // hand back the results of the functions, which allows functions to be
        // used as resolvers for more fine-tuned resolution of these objects.
        //// 如果传入的是闭包，则直接执行闭包函数，返回结果
        if ($concrete instanceof Closure) {
            return $concrete($this, $this->getLastParameterOverride());
        }

        //利用反射机制,解析该类
        $reflector = new ReflectionClass($concrete);

        // If the type is not instantiable, the developer is attempting to resolve
        // an abstract type such as an Interface or Abstract Class and there is
        // no binding registered for the abstractions so we need to bail out.
        if (!$reflector->isInstantiable()) {
            return $this->notInstantiable($concrete);
        }

        $this->buildStack[] = $concrete;

        //取得构造函数
        $constructor = $reflector->getConstructor();

        // If there are no constructors, that means there are no dependencies then
        // we can just resolve the instances of the objects right away, without
        // resolving any other types or dependencies out of these containers.
        // 如果没有构造函数，则表明没有传入参数，也就意味着不需要做对应的上下文依赖解析。
        if (is_null($constructor)) {
            // 将 build 过程的内容 pop，然后直接构造对象输出。
            array_pop($this->buildStack);

            return new $concrete;
        }

        // 获取构造函数的参数
        $dependencies = $constructor->getParameters();

        // Once we have all the constructor's parameters we can create each of the
        // dependency instances and then use the reflection instances to make a
        // new instance of this class, injecting the created dependencies in.
        // 解析出所有上下文依赖对象，带入函数，构造对象输出
        $instances = $this->resolveDependencies(
            $dependencies
        );

        array_pop($this->buildStack);

        return $reflector->newInstanceArgs($instances);
    }


    /**
     *  Resolve all of the dependencies from the ReflectionParameters.
     * @param array $dependencies
     * @return array
     * @throws BindingResolutionException
     * @throws \ReflectionException
     */
    protected function resolveDependencies(array $dependencies)
    {
        $results = [];

        foreach ($dependencies as $dependency) {
            // If this dependency has a override for this particular build we will use
            // that instead as the value. Otherwise, we will continue with this run
            // of resolutions and let reflection attempt to determine the result.
            if ($this->hasParameterOverride($dependency)) {
                $results[] = $this->getParameterOverride($dependency);

                continue;
            }

            // If the class is null, it means the dependency is a string or some other
            // primitive type which we can not resolve since it is not a class and
            // we will just bomb out with an error since we have no-where to go.
            $results[] = is_null($dependency->getClass())
                ? $this->resolvePrimitive($dependency)
                : $this->resolveClass($dependency);
        }

        return $results;
    }

    /**
     * Determine if the given dependency has a parameter override.
     *
     * @param  \ReflectionParameter $dependency
     * @return bool
     */
    protected function hasParameterOverride($dependency)
    {
        return array_key_exists(
            $dependency->name, $this->getLastParameterOverride()
        );
    }

    /**
     * Get a parameter override for a dependency.
     *
     * @param  \ReflectionParameter $dependency
     * @return mixed
     */
    protected function getParameterOverride($dependency)
    {
        return $this->getLastParameterOverride()[$dependency->name];
    }

    /**
     * Get the last parameter override.
     * 获取最后一个参数覆盖。
     * @return array
     */
    protected function getLastParameterOverride()
    {
        return count($this->with) ? end($this->with) : [];
    }


    /**
     *  Resolve a non-class hinted primitive dependency.
     *  解决非类提示的原始依赖关系
     * @param ReflectionParameter $parameter
     * @return mixed|string|null
     * @throws BindingResolutionException
     * @throws \ReflectionException
     */
    protected function resolvePrimitive(ReflectionParameter $parameter)
    {
        if (!is_null($concrete = $this->getContextualConcrete('$' . $parameter->name))) {
            return $concrete instanceof Closure ? $concrete($this) : $concrete;
        }

        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

        $this->unresolvablePrimitive($parameter);
    }


    /**
     * Resolve a class based dependency from the container.
     * 从容器中解析基于类的依赖项
     * @param ReflectionParameter $parameter
     * @return mixed
     * @throws BindingResolutionException
     * @throws \ReflectionException
     */
    protected function resolveClass(ReflectionParameter $parameter)
    {
        try {
            return $this->make($parameter->getClass()->name);
        }

            // If we can not resolve the class instance, we will check to see if the value
            // is optional, and if it is we will return the optional parameter value as
            // the value of the dependency, similarly to how we do this with scalars.
        catch (BindingResolutionException $e) {
            if ($parameter->isOptional()) {
                return $parameter->getDefaultValue();
            }

            throw $e;
        }
    }

    /**
     * Throw an exception that the concrete is not instantiable.
     *
     * @param  string $concrete
     * @return void
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function notInstantiable($concrete)
    {
        if (!empty($this->buildStack)) {
            $previous = implode(', ', $this->buildStack);

            $message = "Target [$concrete] is not instantiable while building [$previous].";
        } else {
            $message = "Target [$concrete] is not instantiable.";
        }

        throw new BindingResolutionException($message);
    }

    /**
     * Throw an exception for an unresolvable primitive.
     *
     * @param  \ReflectionParameter $parameter
     * @return void
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function unresolvablePrimitive(ReflectionParameter $parameter)
    {
        $message = "Unresolvable dependency resolving [$parameter] in class {$parameter->getDeclaringClass()->getName()}";

        throw new BindingResolutionException($message);
    }

    /**
     * Register a new resolving callback.
     * 注册一个新的回调
     * @param  \Closure|string $abstract
     * @param  \Closure|null $callback
     * @return void
     */
    public function resolving($abstract, Closure $callback = null)
    {
        if (is_string($abstract)) {
            $abstract = $this->getAlias($abstract);
        }

        if (is_null($callback) && $abstract instanceof Closure) {
            $this->globalResolvingCallbacks[] = $abstract;
        } else {
            $this->resolvingCallbacks[$abstract][] = $callback;
        }
    }

    /**
     * Register a new after resolving callback for all types.
     *
     * @param  \Closure|string $abstract
     * @param  \Closure|null $callback
     * @return void
     */
    public function afterResolving($abstract, Closure $callback = null)
    {
        if (is_string($abstract)) {
            $abstract = $this->getAlias($abstract);
        }

        if ($abstract instanceof Closure && is_null($callback)) {
            $this->globalAfterResolvingCallbacks[] = $abstract;
        } else {
            $this->afterResolvingCallbacks[$abstract][] = $callback;
        }
    }

    /**
     * Fire all of the resolving callbacks.
     *
     * @param  string $abstract
     * @param  mixed $object
     * @return void
     */
    protected function fireResolvingCallbacks($abstract, $object)
    {
        $this->fireCallbackArray($object, $this->globalResolvingCallbacks);

        $this->fireCallbackArray(
            $object, $this->getCallbacksForType($abstract, $object, $this->resolvingCallbacks)
        );

        $this->fireAfterResolvingCallbacks($abstract, $object);
    }

    /**
     * Fire all of the after resolving callbacks.
     * 解决所有解决后的回调问题
     * @param  string $abstract
     * @param  mixed $object
     * @return void
     */
    protected function fireAfterResolvingCallbacks($abstract, $object)
    {
        $this->fireCallbackArray($object, $this->globalAfterResolvingCallbacks);

        $this->fireCallbackArray(
            $object, $this->getCallbacksForType($abstract, $object, $this->afterResolvingCallbacks)
        );
    }

    /**
     * Get all callbacks for a given type.
     * 获取给定类型的所有回调
     * @param  string $abstract
     * @param  object $object
     * @param  array $callbacksPerType
     *
     * @return array
     */
    protected function getCallbacksForType($abstract, $object, array $callbacksPerType)
    {
        $results = [];

        foreach ($callbacksPerType as $type => $callbacks) {
            if ($type === $abstract || $object instanceof $type) {
                $results = array_merge($results, $callbacks);
            }
        }

        return $results;
    }

    /**
     * Fire an array of callbacks with an object.
     * 使用对象触发一系列回调
     * @param  mixed $object
     * @param  array $callbacks
     * @return void
     */
    protected function fireCallbackArray($object, array $callbacks)
    {
        foreach ($callbacks as $callback) {
            $callback($object, $this);
        }
    }

    /**
     * Get the container's bindings.
     * 获取容器的绑定
     * @return array
     */
    public function getBindings()
    {
        return $this->bindings;
    }

    /**
     * Get the alias for an abstract if available.
     * 如果存在得到一个抽象类的别名
     * @param  string $abstract
     * @return string
     *
     * @throws \LogicException
     */
    public function getAlias($abstract)
    {
        if (!isset($this->aliases[$abstract])) {
            return $abstract;
        }

        if ($this->aliases[$abstract] === $abstract) {
            throw new LogicException("[{$abstract}] is aliased to itself.");
        }

        return $this->getAlias($this->aliases[$abstract]);
    }

    /**
     * Get the extender callbacks for a given type.
     * 获取给定的抽象类型的扩展回调函数
     * @param  string $abstract
     * @return array
     */
    protected function getExtenders($abstract)
    {
        $abstract = $this->getAlias($abstract);

        if (isset($this->extenders[$abstract])) {
            return $this->extenders[$abstract];
        }

        return [];
    }

    /**
     * Remove all of the extender callbacks for a given type.
     * 删除给定类型的所有扩展程序回调。
     * @param  string $abstract
     * @return void
     */
    public function forgetExtenders($abstract)
    {
        unset($this->extenders[$this->getAlias($abstract)]);
    }

    /**
     * Drop all of the stale instances and aliases.
     * 删除抽象类所有的陈旧的实例和别名
     * @param  string $abstract
     * @return void
     */
    protected function dropStaleInstances($abstract)
    {
        unset($this->instances[$abstract], $this->aliases[$abstract]);
    }

    /**
     * Remove a resolved instance from the instance cache.
     * 从实例缓存中删除已经解析的实例
     * @param  string $abstract
     * @return void
     */
    public function forgetInstance($abstract)
    {
        unset($this->instances[$abstract]);
    }

    /**
     * Clear all of the instances from the container.
     * 删除所有的实例
     * @return void
     */
    public function forgetInstances()
    {
        $this->instances = [];
    }

    /**
     * Flush the container of all bindings and resolved instances.
     * 清空容器所有的binding,resolved,instance
     * @return void
     */
    public function flush()
    {
        $this->aliases = [];
        $this->resolved = [];
        $this->bindings = [];
        $this->instances = [];
        $this->abstractAliases = [];
    }

    /**
     * Set the globally available instance of the container.
     *
     * @return static
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * Set the shared instance of the container.
     * 设置container为共享的 实例
     * @param  \Illuminate\Contracts\Container\Container|null $container
     * @return \Illuminate\Contracts\Container\Container|static
     */
    public static function setInstance(ContainerContract $container = null)
    {
        return static::$instance = $container;
    }

    /**
     * Determine if a given offset exists.
     * 判断 bindings[$key] 是否存在。
     * @param  string $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return $this->bound($key);
    }


    /**
     *  Get the value at a given offset.
     * 解析(实例化)对象，调用 make() 方法
     * @param mixed $key
     * @return mixed|object|void
     * @throws BindingResolutionException
     * @throws \ReflectionException
     */
    public function offsetGet($key)
    {
        return $this->make($key);
    }

    /**
     * Set the value at a given offset.
     * 如果 $value 是一个闭包函数，就直接绑定
     * $container['test'] = function() {
     *     echo 'this is a test';
     * };
     * 如果 $value 不是闭包函数，就先将 $value 重新赋值为一个闭包
     * 注册对象到容器，$container['foo', $foo] 等同 $container->bind('foo', 'Acme\Foo')
     * @param  string $key
     * @param  mixed $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        $this->bind($key, $value instanceof Closure ? $value : function () use ($value) {
            return $value;
        });
    }

    /**
     * Unset the value at a given offset.
     * 会同时释放 bindings[$key], $instances[$key], $resolved[$key]
     * 因此调用 unset() 之后，就不能以任何形式从容器实例化对象了
     * ??flush所有
     * @param  string $key
     * @return void
     */
    public function offsetUnset($key)
    {
        unset($this->bindings[$key], $this->instances[$key], $this->resolved[$key]);
    }

    /**
     * Dynamically access container services.
     *
     * @param  string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this[$key];
    }

    /**
     * Dynamically set container services.
     *  动态的设置容器服务
     * @param  string $key
     * @param  mixed $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this[$key] = $value;
    }
}
