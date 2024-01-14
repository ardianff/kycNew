sudo rsync -azvv --exclude-from '/var/www/html/kycNew/exclude-rsync.txt' /var/www/html/kycNew/ -e "ssh -p 7016" candilama@119.2.50.170:/var/www/html/kyc
