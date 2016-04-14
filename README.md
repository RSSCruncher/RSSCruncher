RSS Rest Cruncher
=================

> Note: this project is in an early stage.

###  Generate client:

```sh
php app/console arthurhoaro:oauth-server:client:create --redirect-uri="http://clinet.local/" --grant-type="authorization_code" --grant-type="password" --grant-type="refresh_token" --grant-type="token" --grant-type="client_credentials"
```

### Retrieve the token

http://localhost:8000/oauth/v2/token?client_id=5_ebg354gknv48kc88o8oogwokckco0o40sc000cowc8soosw0k&client_secret=5ub5upfxih0k8g44w00ogwc4swog4088o8444sssos8k888o8g&grant_type=client_credentials

### Access a route

http://localhost:8000/api/v1/feeds.json?access_token=<token>