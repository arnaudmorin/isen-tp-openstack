#!/bin/bash
# Flush iptables
iptables -t mangle -F
# Remove all rules
iptables -t mangle -X
# Create filter rtp_packets
iptables -t mangle -N rtp_packets
# Mark all rtp_packets with 0x8 
iptables -t mangle -A rtp_packets -j MARK --set-mark 0x8
# Route all outgoing UDP packet on ports 10000 to 20000 as RTP packets
iptables -t mangle -A OUTPUT --proto udp --sport 10000:20000 -j rtp_packets
# Show result
iptables -t mangle -L -n -v