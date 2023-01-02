## Marina
Manage your containers with ease.


```
docker run \
    -v ~/apps:/var/www/html/storage/app/marina \
    -v /var/run/docker.sock:/var/run/docker.sock \
    -p 8000:80 \
    finuras/marina
```

```
version: "3.7"

services:
    marina:
        image: finuras/marina
        volumes:
          - ~/apps:/var/www/html/storage/app/marina
          - /var/run/docker.sock:/var/run/docker.sock
        ports:
          - "8000:80"
```
