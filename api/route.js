"use strict";

module.exports = function(app) {
  app.get("/", function(req, res) {
    res.json({
      status: 200,
      data: "ok!"
    });
  });

  //Create ether wallet address
  app.get("/create", function(req, res) {
    //JSON response of wallet address and data
    res.json(wallet.createRandom());
  });

  //Get wallet balance both ether and token
  //@params address
  app.get("/ethbalance/:address", function(req, res) {
    //Get ether address
    var address = req.params.address;

    //Get address balance
    provider.getBalance(address).then(function(balance) {
      // balance is a BigNumber (in wei); format is as a sting (in ether)
      var etherString = ethers.utils.formatEther(balance);
      res.json({ eth: etherString });
    });
  });

  // ----------------------------------------------------

  //Get wallet balance both ether and token
  //@params address
  app.get("/balance/:address", function(req, res) {
    //Get ether address
    var address = req.params.address;

    //Get address balance
    provider.getBalance(address).then(function(balance) {
      // balance is a BigNumber (in wei); format is as a sting (in ether)
      var etherString = ethers.utils.formatEther(balance);

      //Call contract function to check balance of token
      var calls = contract.balanceOf(req.params.address);

      calls
        .then(function(balance) {
          // balance is a BigNumber (in wei); format is as a sting (in ether)
          var token = ethers.utils.formatEther(balance[0]._bn);
          res.json({ eth: etherString, token: token });
        })
        .catch(err => {
          //res.json(err);
          res.json({ eth: "NA", token: "NA" });
        });
    });
  });

  // ----------------------------------------------------

  //Transfer token from one address to another by from private key
  //@params from private key, to, value
  app.get("/muttx/:key/:to/:value", function(req, res) {
    //get private key from url param
    var privateKey = req.params.key;

    //Create wallet from private key
    var wallet = new ethers.Wallet(privateKey, provider);

    //Create contract of wallet
    var contract = new ethers.Contract(cAddress, abi, wallet);

    //Call contract transfer function
    var calls = contract.transfer(
      req.params.to,
      ethers.utils.parseEther(req.params.value)
    );

    //on execute then if success
    calls
      .then(function(success) {
        res.json(success);
      })
      //If error
      .catch(err => {
        res.json(err);
      });
  });

  // //Transfer ether from one address to another
  // //@params from private key, to, amount
  // app.get('/ethtxtest/:key/:to/:amount', function(req, res){

  // 	//Get private key of from address
  // 	var privateKey = req.params.key;

  // 	//Create wallet from private key
  // 	var wallet = new ethers.Wallet(privateKey);

  // 	//Set wallet provider
  // 	wallet.provider = ethers.providers.getDefaultProvider('ropsten');

  // 	// We must pass in the amount as wei (1 ether = 1e18 wei), so we use
  // 	// this convenience function to convert ether to wei.
  // 	var amount = ethers.utils.parseEther(req.params.amount);

  // 	//Options to set gas limit and gas price
  // 	var options = {
  // 		gasLimit: 21000
  // 	};

  // 	//Address to which eth will be transfered
  // 	var address = req.params.to;
  // 	var sendPromise = wallet.send(address, amount, options);

  // 	//If function execute then if success
  // 	sendPromise.then(function(transactionHash) {
  // 	    res.json(transactionHash);
  // 	})
  // 	//If error
  // 	.catch((err) => {
  // 		res.json(err);
  // 	});
  // });

  app.get("/ethtx/:key/:to/:amount", function(req, res) {
    var privateKey = req.params.key;
    var wallet = new ethers.Wallet(privateKey);
    wallet.provider = ethers.providers.getDefaultProvider("homestead");

    var transaction = {
      // Recommendation: omit nonce; the provider will query the network
      // nonce: 0,

      // Gas Limit; 21000 will send ether to another use, but to execute contracts
      // larger limits are required. The provider.estimateGas can be used for this.
      gasLimit: 21000,
      //1000000

      // Recommendations: omit gasPrice; the provider will query the network
      gasPrice: ethers.utils.bigNumberify("41000000000"),

      // Required; unless deploying a contract (in which case omit)
      to: req.params.to,

      // Optional
      data: "0x",

      // Optional
      value: ethers.utils.parseEther(req.params.amount)

      // Recommendation: omit chainId; the provider will populate this
      // chaindId: providers.Provider.chainId.homestead
    };

    // Estimate the gas cost for the transaction
    //var estimateGasPromise = wallet.estimateGas(transaction);

    //estimateGasPromise.then(function(gasEstimate) {
    //    console.log(gasEstimate);
    //});

    // Send the transaction
    var sendTransactionPromise = wallet.sendTransaction(transaction);

    sendTransactionPromise
      .then(function(transactionHash) {
        res.json(transactionHash);
      })
      //If error
      .catch(err => {
        res.json(err);
      });
  });
};
