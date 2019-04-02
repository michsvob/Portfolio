#[{
#    date: "20180118",
#    Machine1: 8,
#    Machine2: 9
#},
#{
#    date: "20180118",
#    Machine1: 8,
#    Machine2: 9
#}]

library(stringr)

dates=seq.Date(as.Date("2018-01-01"),as.Date("2019-01-01"),by="day")
m1=round(10*sin(seq_along(dates)/20))^2
m2=round(10*abs(rnorm(dates,3)))
m3=round(10*log((10*abs(rnorm(dates,3)))))
m4=rbinom(dates,100,.4)
m5=rbinom(dates,600,.1)
m6=round(rbinom(dates,100,.1)*(sqrt(seq_along(dates))))

df=data.frame(dates,m1,m2,m3,m4,m5,m6)

s="["
for(i in seq_along(dates)){
    s=paste0(s,'{date:','"',format(df[i,1],"%Y%m%d"),'"',
               ', Machine1:',df[i,2],', Machine2:',df[i,3],
               ', Machine3:',df[i,4],', Machine4:',df[i,5],
               ', Machine5:',df[i,6],', Machine6:',df[i,7],
               '},')
}

str_sub(s, -1, -1) <- "]"
write(file="ausschussdata.txt",x=s)

dates=seq.Date(as.Date("2018-01-01"),as.Date("2018-10-01"),by="day")
m1=round(abs(10*sin(seq_along(dates)/40)))^2
m2=round(4*abs(rnorm(dates,3)))
m3=round(abs(sin(seq_along(dates)/10)*10*log((10*abs(rnorm(dates,3))))))
m4=rbinom(dates,200,.3)
m5=rbinom(dates,100,.1)
m6=round(rbinom(dates,100,.2)*(sqrt(seq_along(dates))))

df=data.frame(dates,m1,m2,m3,m4,m5,m6)

s="["
for(i in seq_along(dates)){
    s=paste0(s,'{date:','"',format(df[i,1],"%Y%m%d"),'"',
             ', Product1:',df[i,2],', Product2:',df[i,3],
             ', Product3:',df[i,4],', Product4:',df[i,5],
             ', Product5:',df[i,6],
             '},')
}

str_sub(s, -1, -1) <- "]"
write(file="prod_ausschussdata.txt",x=s)
