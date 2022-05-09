---
title: "Overview"
description: "SSH Keys management"
weight: 50
date: "2022-03-01"
lastmod: "2022-03-01"
---

SSH keys are used to access the [SSH protocol](https://www.ssh.com/ssh/protocol/). The SSH protocol uses the client/server model to create an encrypted path of communication that is used for remotely logging into one computer from another. Because of how secure it is, the SSH protocol has become widely used to carry out critical tasks like executing remote commands and managing network infrastructure.

A couple of methods exist for accessing the SSH protocol – passwords and SSH keys. It is not recommended to use passwords because their level of security is relatively low. SSH keys on the other hand can be a very secure method because they are made up of very long strings of [composite numbers and prime factors](https://www.linkedin.com/pulse/2048-bit-encryption-what-why-does-matter-srini-vasan). This is more challenging to crack than passwords, and is why SSH keys are favored over passwords for authenticating to the SSH protocol.

The number of SSH keys within an organization can escalate rather quickly. For example, let’s say a certain group of engineers needs access to the same production server. Each engineer will then have their own set of SSH keys to authenticate to that same production server. As that company grows and that engineer group grows, the number of SSH keys grows too. In fact, companies tend to have hundred (or thousand) of SSH keys. 

## What does SSH key Management mean?

Proper management over any type of resource and its access credentials enables a secure, well controlled environment. You can ensure proper protection of keys with **SSH key management** — a combination of practices, policies, and tools for establishing secure SSH key usage routines within an organization. SSH key management can significantly enhance organization cybersecurity by protecting networks and systems from both external and insider attacks. It can also help an organization comply with cybersecurity standards, laws, and regulations.

At its core, SSH key management will help you:

- Account for all generated SSH keys
- Ensure that no extra keys are generated except for valid reasons
- Know who accesses keys and when
- Avoid reusing keys across systems and users
- Immediately change or revoke access to keys

Without centralized control, **you can’t know how many SSH key pairs are in your organization’s network, who uses them, and how**. And if you mismanage your SSH keys, it will create severe cybersecurity risks.

