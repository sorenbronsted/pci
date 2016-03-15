#!/bin/sh
set -e

dpkg-buildpackage -S
mv ../pci_* /srv/pkg/

