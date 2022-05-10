---
title: "Why ssham?"
description: "The ssham benefits"
weight: 50
date: "2022-03-01"
lastmod: "2022-03-01"
---

Proper management over any type of resource and its access credentials enables a secure, well controlled environment. You can ensure proper protection of keys with **SSH key management** — a combination of practices, policies, and tools for establishing secure SSH key usage routines within an organization. SSH key management can significantly enhance organization cybersecurity by protecting networks and systems from both external and insider attacks. It can also help an organization comply with cybersecurity standards, laws, and regulations.

At its core, SSH key management will help you:

- Account for all generated SSH keys
- Ensure that no extra keys are generated except for valid reasons
- Know who accesses keys and when
- Avoid reusing keys across systems and users
- Immediately change or revoke access to keys

Without centralized control, **you can’t know how many SSH key pairs are in your organization’s network, who uses them, and how**. And if you mismanage your SSH keys, it will create severe cybersecurity risks.

**SSH Access Manager** - ssham - is a comprehensive access security management platform that permits IT professionals to easily establish and maintain an enterprise-wide SSH access security solution from a central location.

It enables a team of system administrators to centrally manage and deploy SSH keys. This app is intended to be used in rather large environment where access to unix accounts are handled with SSH keys.

## 1. Find and inventory all SSH keys

You can’t protect those SSH keys whose existence you don’t know about. So the first step in managing SSH keys is finding all existing keys and associating them with users and servers. 

You can discover all existing keys within your IT infrastructure and learn what servers, machines, or systems they have access to. Thus, you can find potential vulnerabilities like duplicate or orphaned keys and fix them, as well as identify who’s using what keys to access which systems.

## 2. Implement the principle of least privilege

Privileged users should always have access rights only to the data and systems they need for work purposes.

To make this happen, configure individual SHH keys for these employees that allow them to access only the systems or machines they need to perform their duties. Also, make sure to review access rights when employees shift to another position. Avoid shared accounts because they make it impossible to identify who used a pair of keys.

## 3. Eliminate hard-coded and old SSH keys

As we mentioned earlier, SSH keys can be embedded in code or scripts, which can create dangerous backdoors for malware and hackers to exploit. Make sure to check your code for embedded SSH keys, bring them under centralized management, and remove them from applications.

Old keys include orphaned and forgotten unaudited keys that can be exploited by cybercriminals. To avoid their appearance, it’s essential to:

- Terminate access to remote servers for any accounts associated with employees that leave an organization
- Keep track of all generated SSH keys
- Reduce the number of duplicate keys

## 4. Set clear rules for SSH key use and management

Implement an SSH key management policy or make sure your current security policy has all the rules for proper SSH key management. Policies and procedures must establish consistent baseline requirements across all environments where SSH keys are used. These policies should clearly spell out roles and responsibilities to prevent security lapses.

Key questions to cover with SSH security policies and procedures:

- Who can create keys and for what purposes? - ssham could help to assing roles and groups to define who can create keys and for what purposes.
- What is the procedure of requesting a pair of keys for an employee? - ssham becomes the tool for creating and distributing keys.
- How many users can be associated with a single key?- given that ssham makes key creation a really easy task, key sharing is not needed anymore.
- What should happen to keys when the user associated with them leaves the organization or switches to another position? - ssham will easily revoke these keys.