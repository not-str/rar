library(dplyr)
train = read.csv("train.csv")
View(train)
cleaned_train = train %>% select(-c(PassengerId,Name,Ticket,Fare,Cabin))
View(cleaned_train)
cleaned_train = cleaned_train %>% mutate(Pclass = factor(Pclass,levels = c(1,2,3),labels = c("Upper","Middle","Lower")))
View(cleaned_train)
cleaned_train = cleaned_train %>% mutate(Survived = factor(Survived,levels = c(0,1),labels=c("Survived","Not Survived")))

View(cleaned_train)
dim(cleaned_train)
cleaned_train = na.omit(cleaned_train)
dim(cleaned_train)
dt = sort(sample(nrow(cleaned_train),nrow(cleaned_train)*0.7))
train_train = cleaned_train[dt,]
train_test = cleaned_train[-dt,]
dim(train_test)
dim(train_train)

library(rpart)
library(rpart.plot)
train_model = rpart(Survived~.,data=train_train,method="class")
rpart.plot(train_model,extra=106)
predicted = predict(train_model,train_test,type="class")
predicted
summary(predicted)

library(caret)
cf = confusionMatrix(train_test$Survived,predicted)
cf
