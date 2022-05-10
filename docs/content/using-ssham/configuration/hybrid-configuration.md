---
title: "Hybrid configuration"
description: "Configure how the bastion host will communicate with the rest of remote servers"
weight: 50
date: "2022-05-10"
lastmod: "2022-05-10"
---

{{< figure src="/hybrid-configuration.jpg" >}}

## Hybrid mode

If hybrid mode is enabled, the remote hosts will accept other access keys non-managed by SSHAM.

## File to keep the managed keys

File on remote hosts where SSHAM will generate the managed access keys.

## File containing keys non-managed by SSHAM

File on remote hosts where you maintain access keys non-managed by SSHAM.
