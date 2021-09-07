# Maviance Senior Developer Test
 Maviane Test for Senior Developer
 
# 7.1- Business purpose of the snipet

Synchronizing the account balance of a given client with his balance in the balance table.

# 7.2- Code corrections/improvements

 - _Monolog_ instantiation, and provide it a Handler 
 - “BalanceRepositoryInterface _“$erm”_ not explicit naming
 - Try/Catch sequence of the refresh method not correctly performing the business logic
-  refresh method logic error-prone: we need to fetch the client balance before 
 - getBalance is useless
 - Possible to reorganize Models and their Interfaces _(BaseClient BaseClient/ BaseClientInterface and Balance/ BalanceInterface)_,  Repositories and their Interfaces _(BaseClientRepository/ BaseClientRepositoryInterface, BalanceRepository/ BalanceRepositoryInterface)_ , Services and Managers structure so as to be uniformely structured over the application.
 - Add a generic repository abstraction that will handle Database Access so as we no more write SQL in Model Repositories. That will highly reduce the amount of code in those repositories

Checkout some of these corrections/Improvements in the push I just made:
I renamed the _snipet.php_ into _BalanceManager.php_ ; check it here https://github.com/BryZeNtZa/maviane_dev_test/blob/master/src/Spatter/Services/Balance/BalanceManager.php


# 8- Implementing BalanceRepositoryInterface 
I provided two implementations here : 
https://github.com/BryZeNtZa/maviane_dev_test/blob/master/src/Spatter/Services/Balance/Repository/BalanceRepository.php
https://github.com/BryZeNtZa/maviane_dev_test/blob/master/src/Spatter/Services/Balance/Repository/BalanceRepositoryOther.php

Find tests in the test folder :
https://github.com/BryZeNtZa/maviane_dev_test/tree/master/tests

