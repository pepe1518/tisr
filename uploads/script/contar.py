# -*- coding: iso-8859-15
import sys
import os
if len(sys.argv) >= 2:
 print "El texto '%s' tiene %s caracteres" % (sys.argv[1],len(sys.argv[1]))
else:
 print "Necesito un parámetro"
