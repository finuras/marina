## Marina
Manage your containers with ease.

### Project development overview
Visit [Todos](./todos.md) for a list of things to do.


### Useful links
```bash
https://glances.readthedocs.io/en/latest/index.html
https://github.com/google/cadvisor
```

### Examples

```
docker run \
    -v ~/apps:/var/www/html/storage/app/marina \
    -v /var/run/docker.sock:/var/run/docker.sock \
    -p 8000:80 \
    --pull=always \
    --rm \
    finuras/marina
```

Finuras Marina in Compose
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

Example for Portainer
```
version: "3.7"
 
services:
    portainer:
        image:  portainer/portainer-ce:latest
        volumes:
          - portainer_data:/data
          - /var/run/docker.sock:/var/run/docker.sock
        ports:
          - "8001:8000"
          - "9443:9443"
volumes:
  portainer_data:
```

Example for a "Hello World"
```
version: "3.7"
 
services:
    portainer:
        image:  crccheck/hello-world
        ports:
          - "81:8000"
```
