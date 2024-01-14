sudo rsync -azvv --exclude-from '/var/www/html/kycNew/exclude-rsync.txt' /var/www/html/kycNew/ -e "ssh -p 7031" mijen@119.2.50.170:/var/www/html/kyc
