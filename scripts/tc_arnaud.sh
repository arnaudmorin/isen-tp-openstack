#!/bin/sh
RATE=10kbit
if [ "Z$1" != "Z" ] ; then
 RATE=$1
fi
# Delete root qdisc from ens3 in case that we execute this script for the second time
tc qdisc del dev ens3 root
# Create root qdisc
tc qdisc add dev ens3 root handle 1:0 htb default 1
# Create main class with 1 Gbit traffic
tc class add dev ens3 parent 1:0 classid 1:1 htb rate 1024mbit ceil 1024mbit
# Create class for RTP packets
tc class add dev ens3 parent 1:0 classid 1:2 htb rate $RATE ceil $RATE
# Adding qdiscs to our tree leaves
# SFQ (Stochastic Fairness Queueing) will keep on sending packets at desired $RATE
#tc qdisc add dev ens3 parent 1:2 sfq
# TBF (Token Bucket Filter) will drop packet after latency (50ms here)
tc qdisc add dev ens3 parent 1:2 tbf rate $RATE latency 50ms burst $RATE
# Route packets marked with 0x8 to 1:3 qdisc which is for development server
tc filter add dev ens3 parent 1:0 protocol ip prio 1 handle 8 fw flowid 1:2
# Show result
tc -s class show dev ens3
