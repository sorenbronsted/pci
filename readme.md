# PCI

This is the Php Continues Integration project, which can be controlled with github 
compatible webhooks.

## Setup
On ubuntu you need to have the following sudo because setup the work.php is run as `pci` user
```
www-data ALL=(pci) NOPASSWD: ALL
```
in the /etc/sudoers.d/pci file

For each repository you need to configure the webhooks where the payload url is 
```
http://pci.ufds.org/build.php
```
and content type must be 
```
application/x-www-form-urlencoded
```
and the only trigger is `push`