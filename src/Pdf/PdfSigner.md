# How to sign the PDF using PHP's cryptography functions or libraries

- private key (PEM format) corresponding certificate (PEM format)
- These are used to create the digital signature.

```shell
composer require tecnickcom/tcpdf
```

```shell
composer require setasign/fpdi
```


## TCPDF documentation

https://tcpdf.org/docs/

https://manuals.setasign.com/setapdf-signer-manual/



### Example PEM Private Key (private-key.pem):

```
-----BEGIN RSA PRIVATE KEY-----
MIIEpAIBAAKCAQEAx8JN3j1wLk27eD3gD8VzmC5f+jO8b1F6J1gQ6yUvDr85iJZ5
v8HVxN0I5R2sD5ryz9R8Uff9GAX4c0u+fb2N8iD0s4JrRlA1C+6/3Xvuf6a2tO7O
Q0lXYGpZ61n8Lb19dPAmgsDz2pMewGZTb8EuH3Bt5YY23Q4DQ6XijbY2zrK0bMRG
Vr2Tb7ePmPt3c/BUXFvct8r7WZSJELl7u5IC/G/Tjv5ZOnEtvkOlOQk4EMyGcA6U
...
fNhJl5W0VpPLJ8w=
-----END RSA PRIVATE KEY-----
```


### Example PEM Certificate (certificate.pem):

```
-----BEGIN CERTIFICATE-----
MIIDWTCCAkGgAwIBAgIJAK5F8HkxSQHfMA0GCSqGSIb3DQEBCwUAMEUxCzAJBgNV
BAYTAk5MMRIwEAYDVQQIDAlHcmFuaXRlZDEQMA4GA1UEBwwHSGVhbHRoMRQwEgYD
...
j5ux2TggZbQw==
-----END CERTIFICATE-----
```


