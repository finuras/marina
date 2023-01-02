## Marina
Manage your containers with ease.


```
docker run \
    -v ~/apps:/var/www/html/storage/marina \
    -v /var/run/docker.sock:/var/run/docker.sock \
    -p 8000:8000 \
    finuras/marina
```
