---
title: "SSH settings"
description: "Configure how the bastion host will communicate with the rest of remote servers"
weight: 20
date: "2022-05-10"
lastmod: "2022-05-10"
---

Under this section you can configure how the bastion host will communicate with the rest of remote servers.

{{< figure src="/ssh-settings.jpg" >}}

## Bastion host credentials

### Private key

SSHAM will use this key in order to connect to the remote hosts.

### Public key

Remote hosts will identify SSHAM with this key. This key should be distributed to the remote hosts to allow access without password.

The key distribution could be done using several approaches: ansible, puppet...

## SSH options

### Timeout

Time (seconds) to wait before a SSH connection is timed out.

