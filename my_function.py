import pandas as pd
import numpy as np

def PLcalc(lastweek, thisweek):
 	S= 'S' # prefix
 	T = '.txt' # suffix
 	BS = 'BS'

 	date = str(lastweek) # date
 	FNlast = S+date+T # filename
 	Slast = pd.read_table(FNlast, sep=' ', header=None)

 	date = str(thisweek) # date
 	FNthis = S+date+T # filename
 	Sthis = pd.read_table(FNthis, sep=' ', header=None)

 	date = str(lastweek)
 	FNBSlast = BS+date+T
 	BSlast = pd.read_table(FNBSlast,sep=' ', header=None )
 	#print(BSlast)

 	PL = ((Sthis[1]-Slast[1])/Slast[1]*BSlast[1])+((Sthis[2]-Slast[2])/Slast[2]*BSlast[2])
 	#print(PL)

 	Cthis = BSlast[0]
 	Athis0 = Sthis[1]/Slast[1]*BSlast[1]
 	Athis1 = Sthis[2]/Slast[2]*BSlast[2]
 	BSthis = pd.concat([Cthis, Athis0, Athis1],axis=1)
 	#print(BSthis)

 	NetValue=Cthis+Athis0+Athis1
 	#print(NetValue)

 	date = str(thisweek)
 	BSthis.to_csv(BS+date+T, sep=' ', header=False, index=False)
 	return PL

def  var_calc(thisweek,term):
	S = 'S' # prefix
	T = '.txt' # suffix
	BS = 'BS'

	names = ['cash','tyo','ben']
	r = []
	for name in names:
		FileName = name + '.csv'
		df = pd.read_csv(FileName)
		a_df = df.values
		itemcounter = 0
		for item in a_df:
			if itemcounter ==0:
				r1 = []
				itemcounter +=1
			else:
				valuetoday = a_df[itemcounter][0]
				valueyesterday = a_df[itemcounter-1][0]
				returntoday = (valuetoday-valueyesterday)/valueyesterday
				r1.append(returntoday)
				itemcounter +=1
		r.append(r1)

	df = pd.DataFrame(data = r, index = names)
	#print(df)

	#toyota = df.iloc[1,:]
	#sony = df.iloc[2,:]

	mu = df.mean(axis=1)
	#print(mu)
	date = str(thisweek)
	FNBSthis = BS+date+T
	bs = pd.read_table(FNBSthis, sep=' ', names = names)
	bst = bs.T

	bsa = np.array(bs)
	bsta = np.array(bst)
	#print(bsa)
	#print(bsta)

	dot = np.dot(bsa, mu) #行列の内積
	#print(dot)

	cov = np.cov(df, rowvar = 1, bias = 1) #共分散行列
	#print(cov)

	#dot3 = np.dot(np.dot(bsa, cov), bsta)
	#print(dot3)

	dot3 = bsa@cov@bsta #行列の掛算3つ以上
	#print(dot3)
	T = term
	VaR = -dot*T + 2.33*np.sqrt(dot3*T)
	#print(VaR)
	return VaR