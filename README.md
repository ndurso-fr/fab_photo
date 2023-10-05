# fab_photo

#### Config using gmail as smtp

* Into .env.local
  MAILER_DSN=gmail://monamil@gmail.com:password?verify_peer=0
* Password 
  Into gmail accompt set a application secret : https://support.google.com/accounts/answer/185833

* Inot config/packages/messenger.yaml comment ligne below :
  Symfony\Component\Mailer\Messenger\SendEmailMessage: async

#### Intallation 

```bash
composer require symfony/google-mailer
```

```bash
composer require symfonycasts/verify-email-bundle
```

#### Tests

```bash
composer require --dev symfony/test-pack
```
=> create Unit folder into tests folder

```bash
php bin/console make:test
```
(answer : TestCase)

```bash
php bin/phpunit tests/Unit/BasicTest.php 
```



### Panther Tests

1. Installation
```bash
composer req symfony/panther
composer require --dev dbrekelmans/bdi
```

2.  Verify drivers installation :
```bash
vendor/bin/bdi detect drivers
```

=> create E2E folder into tests folder

```bash
php bin/console make:test
```
=> answer : PantherTestCase
=> E2E\BasicTest

3. Configuration

Into phpunit.xml.dist add :

```xml
<server
name="DATABASE_URL"
value="mysql://root:@127.0.0.1:3306/fab_photo"
force="true"
/>
```