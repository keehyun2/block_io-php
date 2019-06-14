<!-- /* This example script does the following:
   
   1. Get available balance in your account for Dogecoin, or Litecoin, or Bitcoin, etc.
   2. Create an address labeled 'shibetime1' on the account if it does not already exist
   3. Withdraw 1% of total available balance in your account, and send it to the address labeled 'shibetime1'
   
   IMPORTANT! Specify your own API Key and Secret PIN in this code. Keep your Secret PIN safe at all times.

   Contact support@block.io for any help with this.
*/ -->

<?php
require_once '../lib/block_io.php';

/* Replace the $apiKey with the API Key from your Block.io Wallet. A different API key exists for Dogecoin, Dogecoin Testnet, Litecoin, Litecoin Testnet, etc. */
$apiKey = 'YOUR DOGECOIN TESTNET API KEY';
$pin = 'SECRET PIN';
$version = 2; // the API version

$block_io = new BlockIo($apiKey, $pin, $version);

echo "*** Getting account balance\n <br>";

$getBalanceInfo = "";

try {
    $getBalanceInfo = $block_io->get_balance();
    
    echo "!!! Using Network: ".$getBalanceInfo->data->network."\n <br>";
    echo "Available Amount: ".$getBalanceInfo->data->available_balance." ".$getBalanceInfo->data->network."\n <br>";
} catch (Exception $e) {
   echo $e->getMessage() . "\n <br>";
}

echo "\n <br>\n <br>";


// echo "*** Create new address\n <br>";

// $getNewAddressInfo = "";

// try {
//     $getNewAddressInfo = $block_io->get_new_address(array('label' => '111ddzxczxc'));

//     echo "New Address: ".$getNewAddressInfo->data->address."\n <br>";
//     echo "Label: ".$getNewAddressInfo->data->label."\n <br>";
// } catch (Exception $e) {
//     echo $e->getMessage() . "\n <br>";
// }

// echo "\n <br>\n <br>";

try {
    echo "Getting address for Label='default'\n <br>";
    $getAddressInfo = $block_io->get_address_by_label(array('label' => 'default'));
    echo "Status: ".$getAddressInfo->status."\n <br>";
} catch (Exception $e) {
    echo $e->getMessage() . "\n <br>";
}

echo "Label has Address: " . $block_io->get_address_by_label(array('label' => 'default'))->data->address . "\n <br>";

echo "\n <br>\n <br>";

echo "***Send 10% of coins on my account to the address labeled 'shibetime1'\n <br>";

// Use high decimal precision for any math on coins. They can be 8 decimal places at most, or the system will reject them as invalid amounts.
$sendAmount = bcmul($getBalanceInfo->data->available_balance, '0.01', 8); 

echo "Available Amount: ".$getBalanceInfo->data->available_balance." ".$getBalanceInfo->data->network."\n <br>";

echo "sendAmount: ".$sendAmount."\n <br>";

echo $getAddressInfo->data->address."\n <br>";
# detour: let's get an estimate of the network fee we'll need to pay for this transaction
# use the same parameters you will provide to the withdrawal method get an accurate response
// $estNetworkFee = $block_io->get_network_fee_estimate(array('to_address' => $getAddressInfo->data->address, 'amount' => $sendAmount));
$estNetworkFee = $block_io->get_network_fee_estimate(array('to_address' => '2NDSXLUpxNRmNQy1iEYgjWJKjWtAy8rDEBc', 'amount' => $sendAmount));

echo "Estimated Network Fee: " . $estNetworkFee->data->estimated_network_fee . " " . $estNetworkFee->data->network . "\n <br>";

echo "Withdrawing 1% of Available Amount: ".$sendAmount." ".$getBalanceInfo->data->network."\n <br>";

try {
    // $withdrawInfo = $block_io->withdraw(array('to_address' => $getAddressInfo->data->address, 'amount' => $sendAmount));
    $withdrawInfo = $block_io->withdraw(array('to_address' => '2NDSXLUpxNRmNQy1iEYgjWJKjWtAy8rDEBc', 'amount' => $sendAmount));
    echo "Status: ".$withdrawInfo->status."\n <br>";

    echo "Executed Transaction ID: ".$withdrawInfo->data->txid."\n <br>";
    echo "Network Fee Charged: ".$withdrawInfo->data->network_fee." ".$withdrawInfo->data->network."\n <br>";
} catch (Exception $e) {
   echo $e->getMessage() . "\n <br>";
}

?>
