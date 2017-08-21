# mipush
[![Latest Stable Version](http://www.maiguoer.com/haiaouang/mipush/stable.svg)](https://packagist.org/packages/haiaouang/mipush)
[![License](http://www.maiguoer.com/haiaouang/mipush/license.svg)](https://packagist.org/packages/haiaouang/mipush)

laravel小米推送包

## 安装

在你的终端运行以下命令

`composer require haiaouang/mipush`

或者在composer.json中添加

`"haiaouang/mipush": "1.0.*"`

然后在你的终端运行以下命令

`composer update`

安装依赖包 [haiaouang/support](https://github.com/haiaouang/support)

安装依赖包 [haiaouang/pusher](https://github.com/haiaouang/pusher)

在配置文件中添加 config/app.php

```php
    'providers' => [
        /**
         * 添加供应商
         */
        Hht\Pusher\PusherServiceProvider::class,
        /**
         * 添加供应商
         */
        Hht\Support\ServiceProvider::class,
    ],
```

生成配置文件

`php artisan vendor:publish`

设置推送信息的参数 config/pushers.php

## 调用

修改config/pushers.php对应的配置

```php
<?php

return [
    'default' => 'mipush',
    'launchers' => [
        'mipush' => [
            'driver' => 'mipush',
            'reg_url' => 'https://api.xmpush.xiaomi.com/v3/message/regid',
            'alias_url' => 'https://api.xmpush.xiaomi.com/v3/message/alias',
            'topic_url' => 'https://api.xmpush.xiaomi.com/v3/message/topic',
            'multi_topic_url' => 'https://api.xmpush.xiaomi.com/v3/message/multi_topic',
            'all_url' => 'https://api.xmpush.xiaomi.com/v3/message/all',
            'exist_url' => 'https://api.xmpush.xiaomi.com/v2/schedule_job/exist',
            'delete_url' => 'https://api.xmpush.xiaomi.com/v2/schedule_job/delete',

            'android' => [
                'bundle_id' => '',
                'app_id' => '',
                'app_key' => '',
                'app_secret' => ''
            ],

            'ios' => [
                'bundle_id' => '',
                'app_id' => '',
                'app_key' => '',
                'app_secret' => ''
            ],
            'prefix' => env( 'MIPUSH_PREFIX' , 'test_' )
        ],
    ],
];
```

创建message(消息发送只能发给message对应的端)

```php
    //苹果message -- 具体参数配置清查看小米推送文档
    $message = new \Hht\MiPush\Builder\IOSBuilder();
    
    //安卓message -- 具体参数配置清查看小米推送文档
    $message = new \Hht\MiPush\Builder\Builder();
```

### 根据别名发送 -- 单个(别名会自动添加配置内的前缀)

```php
    Push::launcher('mipush')->setAlias('aaa')->send($message);
```

### 根据别名发送 -- 多个(别名会自动添加配置内的前缀)

```php
    Push::launcher('mipush')->setAliases('aaa', 'bbb', 'ccc')->send($message);
    //或
    Push::launcher('mipush')->setAliases(['aaa', 'bbb', 'ccc'])->send($message);
```

### 根据id发送 -- 单个

```php
    Push::launcher('mipush')->setId('aaa')->send($message);
```

### 根据id发送 -- 多个

```php
    Push::launcher('mipush')->setIds('aaa', 'bbb', 'ccc')->send($message);
    //或
    Push::launcher('mipush')->setIds(['aaa', 'bbb', 'ccc'])->send($message);
```

### 根据标签发送(标签发会自动添加配置内的前缀)

```php
    Push::launcher('mipush')->setTopic('aaaa')->send($message);
```

### 发给所有用户

```php
    Push::launcher('mipush')->setAll(true)->send($message);
```

## 依赖包

* haiaouang/support : https://github.com/haiaouang/support
* haiaouang/pusher : https://github.com/haiaouang/pusher
