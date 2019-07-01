# login to the app
curl -X POST -F "email=test1@test.com" -F "pass=test1test1" http://localhost:8888/parcel_pool_web_service/parcel_pool_test.php?page=login.html

# add a parcel
curl -X POST -F "from=Brisbane" -F "to=Tehran" -F "poolId=1" -F "userId=1" -F "weight=5" http://localhost:8888/parcel_pool_web_service/parcel_pool_test.php?page=addparcel.html

# searching for pool using selected cities
curl -X POST -F "from=Brisbane" -F "to=Tehran" http://localhost:8888/parcel_pool_web_service/parcel_pool_test.php?page=showpools.html

# search for pool without choosing the cities
curl -X POST -F "from=From" -F "to=To" http://localhost:8888/parcel_pool_web_service/parcel_pool_test.php?page=showpools.html

