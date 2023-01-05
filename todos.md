# Marina - Todos

- Every feature is behind authentication. Auth related tasks is covered by Breeze/Filament.
- [ ] The default user is `marina` with password `marina`.

### Initial setup
- [ ] The initial setup should be done by a script.
  - The script should be able to run on the host.
  - Let's think about concerns:
    - [ ] User:
      - The script is run by `root` user.
      - The script is run by an existing user `nickname` id&gid=`1000:1000`. Must be sudoer.
    - [ ] Docker:
      - Is Docker installed?
      - Is Compose a plugin `docker-compose` or `docker compose`? Or just based on Docker version?

### Global settings
  - [ ] Define the `Marina` folder - default should be `~/projets`

### Projects
- [ ] Create a new project
    This is essentially to create a new folder inside the `Marina` folder.
    Just support non-Swarm apps. 
  - [ ] Create a new project from a Docker Compose file (text field to input)
  - [ ] Create a new project from a Template (App menu)
  - [ ] Create a new project from a Git repository (for now, just support a deployment key)
    - [ ] Create a new project Builder. It just builds images. Pushes them to (private or not) registries.
- [ ] Manage an existing app
  - [ ] Edit Docker Compose file
  - [ ] Edit `.env` file
  - [ ] Edit deploy script
  - [ ] Run one-off custom scripts
  - [ ] Edit custom scripts

### Remotes
- [ ] Manage Registries (authentication to be able to push images).
- [ ] Manage SSH keys (connects to remotes to install projects and run scripts)
- [ ] Manage Agents via API
- [ ] Manage Agents webhooks (agent with reverse API)
  - There's a version running in a remote, that expose SSH, and also not HTTP port for the agent.
  - It's polling the "Manager" for new tasks.
