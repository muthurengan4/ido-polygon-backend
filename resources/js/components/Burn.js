import React, { useEffect } from "react";
import Web3 from "web3";
import Token from "../abis/Token.json";
import EthSwap from "../abis/EthSwap.json";

const Burn = () => {
    useEffect(() => {
        loadWeb3();
    }, []);
    const [walletAddress, setWalletAddress] = useState("");

    const [loadinBlockchain, setLoadingBlockchain] = useState(true);

    const [loading, setLoading] = useState(true);

    const [account, setAccount] = useState("");

    const [ethBalance, setEthBalance] = useState("");

    const [token, setToken] = useState("");

    const [tokenBalance, setTokenBalance] = useState("");

    const [ethSwap, setEthSwap] = useState("");

    const [output, setOutput] = useState(0);

    const [etherAmountEntered, setEtherAmountEntered] = useState(0);

    const [buttonContentAddWallet, setButtonContentAddWallet] = useState("");

    const [tokenAmount, setTokenAmount] = useState(0);

    const [tokenSymbol, setTokenSymbol] = useState("");

    const loadWeb3 = async () => {
        if (window.ethereum) {
            window.web3 = new Web3(window.ethereum);
            await window.ethereum.enable();
            console.log("Etherum enabled");
            setLoadingBlockchain(false);
            loadBlockchainData();
            return true;
        } else if (window.web3) {
            window.web3 = new Web3(window.web3.currentProvider);
            setLoadingBlockchain(false);
            loadBlockchainData();
            return true;
        } else {
            window.alert(
                "Non-Ethereum browser detected. You should consider trying MetaMask!"
            );
            return false;
        }
    };

    const loadBlockchainData = async () => {
        const web3 = window.web3;

        const accounts = await web3.eth.getAccounts();
        setAccount(accounts[0]);

        const ethBalance = await web3.eth.getBalance(accounts[0]);
        setEthBalance(ethBalance);

        // Load Token
        const networkId = await web3.eth.net.getId();
        console.log("network", networkId);
        const tokenData = Token.networks[networkId];
        console.log("Token nwtwork", tokenData);
        if (tokenData) {
            const token = new web3.eth.Contract(Token.abi, tokenData.address);
            setToken(token);
            let tokenBalance = await token.methods
                .balanceOf(accounts[0])
                .call();
            let tokenSymbol = await token.methods.symbol().call();
            setTokenSymbol(tokenSymbol);
            setTokenBalance(tokenBalance.toString());
        } else {
            window.alert("Token contract not deployed to detected network.");
        }

        // Load EthSwap
        const ethSwapData = EthSwap.networks[networkId];
        if (ethSwapData) {
            const ethSwap = new web3.eth.Contract(
                EthSwap.abi,
                ethSwapData.address
            );
            console.log("Token address", ethSwapData.address);
            setEthSwap(ethSwap);
        } else {
            window.alert("EthSwap contract not deployed to detected network.");
        }

        setLoading(false);
    };

    return <h3>Hello World</h3>;
};

export default Burn;
