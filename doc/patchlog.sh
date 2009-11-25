#! /bin/bash

git log --name-only --pretty=oneline |
 while read line; do
  if echo "$line" | grep -qe "^[0-9a-f]\{40\} "; then
   patch="$line"
  else
   echo "$line $patch"
  fi
 done > log.txt 

inits="
 fbb00210436f4711ba6407790b346e52ff0ca921
 671181aff7686a28454eeb26f0dac5e0f6b819f6
 2f0c1ff39fecf6e128b165cb8914a792830e757c
 9db436fae59e936193c27c6ad97f776f4e2ad440
 4be88034f39d7e0e9c7a786906d756a47fdc9e32
"

testonly="
 b7861f5d3c103c11264f993ad56f13ba5f475981
 27283a0e2681cf4c961b1324353c77d81ba2caa8
"

reverts="
 9d8a4718c32ccec6b9a5c95838efed454ca5209f f742603f712f037fc71282155a7f4d2af70e17e8
 48c6dd7be89c90e1bf4d9022b6d0c5f706955654 c6b9e2f373e38c0da53756a27b7abd4252c38559
 ef61289b4e1b926c07dc5128d466fd8cabaa7332 7b84f1e2fd0afef2fd126f2078ed6574a5f43135
 b5fdda8fa8d88288ae59a2f4aee9cd12ebc38798 79b0edebd6d53ffb68f0aa8612ad6cf0cc22ff06
 c069dd2fab032b35ba2a6062f9440c9612520f24 f88a673d7cfbcad29f929d0f810139a82b002e30
"

for name in $inits $testonly $reverts; do
 grep -v $name log.txt > log.1.txt; mv log.1.txt log.txt
done

grep modules/ log.txt > log.modules.txt
grep -v modules/ log.txt > log.core.txt

sed -e "s+^.* \([0-9a-f]\{40\} .*\)+\1+g" < log.core.txt | sort | uniq > log.core.versionsonly.txt
sed -e "s+^.* \([0-9a-f]\{40\} .*\)+\1+g" < log.modules.txt | sort | uniq > log.modules.versionsonly.txt

# Grouping
grep -e "9898221e98b1bdd2c42ff5cf0ad3bffe2b0c9e1c\|ac87f5d0f451740f20d81e63b8c4319509c9e968\|bc9850b2535b6a7684d4e1b6ae63c157a8516c3a\|6fbc208620527f04ddf6a955dda870dd096c911d\|b08f270f8012d8dfdabbf6a5043a2cf8664c22f0\|7f91c4ec5d624e24c9cb1c331e4247aef4521650\|fae9bd5577567ac5c2b83a12e1753a8abc1994b7" log.core.versionsonly.txt > "log.core.Testcode&local setup.txt"

grep -e "0101eb1c710df18c057ac973525de4a3c2aa0d1d\|06a9996fa76bf6afd22959aeb7a75816af08530e\|143564399fef759328b25e6858d70182211e9395\|8506eae8c4bc6aa387304d8036423cd52214ae84\|8d36934393563cf9c2249555b3cacc0e8694f8fa" log.core.versionsonly.txt > "log.core.Bugfixes and general enhancements.txt"

grep -e "2f704a92ae968cb3242fadaa2eaa35db5bb61792\|3178faf5de91392ab145a0f707e4d0efcabedc15\|511b73db1df1c181e1140b7ae55b98c336490968\|51d82083a6b09bc9a9e99c127e68b4d40086f061\|54b648c0b01bb8adc1639c7b7cc57fa347e3d8d9\|5d51721e7e5cd31410a52de7d6233bc6cebd0f62\|775c4a71d5054a7c7c64e29854374bc39b736708\|9d5d3c7b78d7bf970e5948e6306ea5cd751c7d02\|8c33848e7a68b145516ed24256378d527e67647d\|9295c9ce174ba39d3d5b28960000179b3be8c506\|9e69d0028792ef7ba43310670b95f902f86cfb12\|c393a808bce4e8422015b721c4f229991f5eaa28\|c79c251a58808f6908b4946a43e9a348c62b90a9\|d3ec03e43852c9dac88e5265513975f8ea19d9f2\|f3189dc267cc5159a1c1ef4a7e09f363cd433168\|fc7726415a325598d4fdad141a7c3c199641374a\|ddb7a54d71ad5e4715fc57c63374a215fba465bc\|b1f1303e15d7339356b4f9b92c6056c767cc8df2\|5740a4cfbf20063d9375a85192cb89d6ddae2603\|c05b6e67b08527ef8eb6a95d324d30e2db2f9f55" log.core.versionsonly.txt > "log.core.Price-per-currency&group.txt"

grep -e "52c1479d7db81b0d82dd1c32aeab4ba073eb5095\|30c89028603dc8e44b730db21d87c8e904531c9b\|34b78007929f8e008f3a0fdb332c0cf694a1bbe0\|3caf94a088ac5d5a7d35ee4d83b0870aac113b76\|4343d60596e4b34744f3043dfe98987802af6c91\|449cb1722e0125465abef8219c503a02eb137573\|59d1ddc7e1012fe95dbe0a2930c0cc7bbd387c47\|607d7d2ac3d2ae8a17f31899aa9040a1aae3e181\|747cbae9edd8b1df072aa43a2f5dc7988e879d35\|c243e13bcd64138ca77612cbc24d415d083cdb61\|c755d1fad1249b6e4e6a5a2e794f7d2a947a57c4\|ad8a5ba8674835fd084e73e818b72a04896ffaaf\|c29e0099593834ef3a8da5883123bc178e468a60\|68d2b319b6bb15ae65a344fc60016fbc374a2845\|7ad9fc81069f5d2c2580342cbf97a349a68d28f6" log.core.versionsonly.txt > "log.core.Product types.txt"

grep -e "09cc7d50732c9e955fc5937a440a3336903d96e0" log.core.versionsonly.txt > "log.core.Config changes.txt"

grep -e "1b51a5bcd9f317a9bf764d13fd78a081e9b24b90" log.core.versionsonly.txt > "log.core.Order process.txt"

grep -e "b38dded769b0fd37a69a7e8d3afa43e0ebc0cbd0\|b75118b7bb028159b6d9107ab2e1f4e539df6858\|bb2c23d77a3ca43670e282aad720bfb62ead707e\|20b9cf3096ab7e94bb3d789f2c420482505f6876\|8efd3f195ce571f554848bca5bbd3e530e04aefb" log.core.versionsonly.txt > "log.core.Per category theme.txt"

grep -e "c6fe2ad5861b727f1232dd24ecd0774fd1f2a45b\|e22bb82f93e56fd837cc855a43cc7c2776a40c1a" log.core.versionsonly.txt > "log.core.Hooks.txt"

grep -e "afe680bec5cd0b90d80b6e4a5f8290beaa2d9eb8" log.core.versionsonly.txt > "log.core.Theme.txt"

grep -e "5b5ec06b56e4afa8773b7ae7aad02b60341807a3\|87e568a166ee5c287cb205837119bb30715653b0" log.core.versionsonly.txt > "log.core.General bugfixes.txt"

grep -e "3e2098da1747a5d3eb59aa42cc7a4ee28e8f2869" log.core.versionsonly.txt > "log.core.Mistakes (not corehacks, checked in some random cachefile).txt"

cat log.core.versionsonly.txt \
 "log.core.Testcode&local setup.txt" \
 "log.core.Bugfixes and general enhancements.txt" \
 "log.core.Price-per-currency&group.txt" \
 "log.core.Product types.txt" \
 "log.core.Config changes.txt" \
 "log.core.Order process.txt" \
 "log.core.Per category theme.txt" \
 "log.core.Hooks.txt" \
 "log.core.Theme.txt" \
 "log.core.General bugfixes.txt" \
 "log.core.Mistakes (not corehacks, checked in some random cachefile).txt" |
  sort | uniq -u > "log.core.New, uncategorized.txt"
