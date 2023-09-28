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
