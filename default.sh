cd './bundles/grinway/'

V="v1"
cd './command-bundle'
git checkout -b "${V}" && git fetch origin "${V}" --tags && git merge "origin/${V}" -Xtheirs -m'auto merge(origin)'
cd '..'

V="v1"
cd './env-processor-bundle'
git checkout -b "${V}" && git fetch origin "${V}" --tags && git merge "origin/${V}" -Xtheirs -m'auto merge(origin)'
cd '..'

V="v2"
cd './service-bundle'
git checkout -b "${V}" && git fetch origin "${V}" --tags && git merge "origin/${V}" -Xtheirs -m'auto merge(origin)'
cd '..'

V="v1"
cd './web-app-bundle'
git checkout -b "${V}" && git fetch origin "${V}" --tags && git merge "origin/${V}" -Xtheirs -m'auto merge(origin)'
cd '..'

cd '../..'

