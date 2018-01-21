./vendor/bin/codecept run tests/acceptance/initialize
./vendor/bin/codecept run tests/acceptance/admin/add
./vendor/bin/codecept run tests/acceptance/admin/other_tests
mv ~/src/tests/_output/records.html ~/src/tests/_output/adminTests.html
./vendor/bin/codecept run tests/acceptance/judge
mv ~/src/tests/_output/records.html ~/src/tests/_output/judgeTests.html
./vendor/bin/codecept run tests/acceptance/team
mv ~/src/tests/_output/records.html ~/src/tests/_output/teamTests.html

