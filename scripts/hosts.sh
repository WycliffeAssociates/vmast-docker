#!/bin/sh

VMAST=$(cat /etc/hosts | grep v-mast.mvc)
VRAFT=$(cat /etc/hosts | grep v-raft.mvc)

ADDR=$(ping -c1 web | sed -nE 's/^PING[^(]+\(([^)]+)\).*/\1/p')

[[ -z "$VMAST" ]] && echo "$ADDR      v-mast.mvc" >> /etc/hosts
[[ -z "$VMAST" ]] && echo "$ADDR      v-raft.mvc" >> /etc/hosts