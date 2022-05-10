---
title: "Application defaults"
description: "Configure how the bastion host will communicate with the rest of remote servers"
weight: 30
date: "2022-05-10"
lastmod: "2022-05-10"
---

{{< figure src="/app-defaults.jpg" >}}

## Remote paths

### Updater script remote path

Path on remote hosts where the `ssham-remote-updater.sh` will be copied.

### authorized_keys remote path

Path of the `authorized_keys` file which contains the SSH keys that can be used for logging into the remote hosts.

### SSH remote port

Default port to reach remote hosts by SSH protocol.

