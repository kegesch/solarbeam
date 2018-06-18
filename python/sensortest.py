import sys
import serial
import time

port = serial.Serial(
	port='/dev/ttyUSB1',
	baudrate=9600,
	parity=serial.PARITY_NONE,
	stopbits=serial.STOPBITS_ONE,
	bytesize=serial.EIGHTBITS
)

start = '1b1b1b1b01010101'
end = '1b1b1b1b1a'

data = ''

while True:
	char = port.read()
	data = data + char.encode('HEX')
	pos = data.find(start)
	if(pos <> -1):
		data = data[pos:len(data)]
	pos = data.find(end)
	if(pos <> -1):
		result = (time.strftime("%Y-%m-%d ") + time.strftime("%H:%M.%S"))
#		search = '070100010801ff'
#		pos = data.find(search)
#		
#		if(pos <> -1):
#			pos = pos + len(search) + 16
#			value = data[pos:pos + 6]
#			result = result + ';' + str(int(value, 16) / 1e2)
#		else:
#			result = result + ';' + 'n/A'
#
		search = '7707'
		endseq = '0177'
		endseq2 = '0163'
		beginat = 0
		messages = ''
		pos = data.find(search, beginat)
		while (pos <> -1):
			endpos = data.find(endseq, pos)
			if(endpos ==  -1):
				endpos = data.find(endseq2, pos)
			if(endpos <> -1):
				beginat = endpos
				messages = messages + '# ' + data[pos:endpos + 2] + '\n'
			pos = data.find(search, beginat)			

		result = result + "\n" + messages + "\n"
		print result
		data = ''
