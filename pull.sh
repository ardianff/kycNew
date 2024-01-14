sudo rsync -azvv --exclude-from '/var/www/html/kycNew/exclude-rsync.txt' -e "ssh -p 9084" server@119.2.50.170:/var/www/html/kyc/ /var/www/html/kyc
