@extends('layouts.admin')

@section('title', tr('projects'))

@section('content-header', tr('project'))

@section('breadcrumb')

    <li class="breadcrumb-item active">
        <a href="{{route('admin.projects.index')}}">{{ tr('projects') }}</a>
    </li>

    <li class="breadcrumb-item">{{tr('view_project')}}</li>

@endsection

@section('content')

    <div class="box">
        
        <div class="box-body">

            <div class="row">
                
                <div class="col-md-12">
                    <div class="media-list media-list-divided">

                        <div class="media media-single">

                            <img class="w-80 border-2" src="{{$project->picture}}" alt="...">
                            
                            <div class="media-body">
                                <h6>{{$project->name}}</h6>
                                <small class="text-fader">{{common_date($project->created_at , Auth::guard('admin')->user()->timezone)}}</small>
                            </div>

                            @if($project->status == APPROVED)

                                <div class="media-right">

                                    <a class="btn btn-warning margin" href="{{  route('admin.projects.status', ['project_id' => $project->id])}}" onclick="return confirm(&quot;{{ $project->name }} - {{ tr('project_decline_confirmation') }}&quot;);">
                                            <i class="fa fa-check"></i> {{ tr('decline') }}
                                    </a>
                                </div>

                            @else

                                <div class="media-right">

                                    <a class="btn bg-navy margin" href="{{ route('admin.projects.status' , ['project_id' => $project->id] ) }}"> <i class="fa fa-check"></i>  {{ tr('approve') }}</a>
                                </div>

                            @endif

                            @if(Setting::get('is_demo_control_enabled') == YES)

                                <div class="media-right">
                                    <button class="btn bg-purple margin"><i class="fa fa-edit"></i> {{tr('edit')}}</button>
                                </div>

                                <div class="media-right">
                                    <button class="btn bg-olive margin"><i class="fa fa-trash"></i> {{tr('delete')}}</button>
                                </div>

                            @else

                                <div class="media-right">

                                    <a class="btn bg-purple margin" href="{{ route('admin.projects.edit', ['project_id' => $project->id] ) }}"><i class="fa fa-edit"></i> {{tr('edit')}}</a>
                                </div>  

                                <div class="media-right">

                                    <a class="btn bg-olive margin" onclick="return confirm(&quot;{{ tr('user_delete_confirmation' , $project->name) }}&quot;);" href="{{ route('admin.projects.delete', ['project_id' => $project->id] ) }}"><i class="fa fa-trash"></i> {{tr('delete')}}</a>
                                </div>

                               

                            @endif

                            @if(in_array($project->publish_status, [PROJECT_PUBLISH_STATUS_INITIATED, PROJECT_PUBLISH_STATUS_SCHEDULED]) && $project->status == APPROVED)

                                <a class="btn btn-primary margin" href="{{  route('admin.projects.publish_status' , ['project_id' => $project->id, 'publish_status' => PROJECT_PUBLISH_STATUS_OPENED] )  }}" onclick="return confirm(&quot;{{ $project->name }} - {{ tr('are_you_sure') }}&quot;);"><i class="fa fa-folder-open"></i> {{ tr('mark_as_opened') }}
                                </a>

                            @endif

                            @if(in_array($project->publish_status, [PROJECT_PUBLISH_STATUS_OPENED]))

                                <a class="btn btn-warning margin" href="{{  route('admin.projects.publish_status' , ['project_id' => $project->id, 'publish_status' => PROJECT_PUBLISH_STATUS_CLOSED] )  }}" onclick="return confirm(&quot;{{ $project->name }} - {{ tr('are_you_sure') }}&quot;);"><i class="fa fa-folder"></i> {{ tr('mark_as_closed') }}
                                </a>

                            @endif

                            <a class="btn btn-success margin" href="{{ route('admin.invested_projects' , ['project_id' => $project->id] ) }}"><i class="fa fa-user"></i> {{ tr('invested_users') }}</a>

                        </div>
                    </div>
                </div>
            </div>
        
        </div>
    
    </div>  

    <div class="row">

        <div class="col-lg-12 col-12">

            <div class="box box-inverse bg-pale-yellow">
                <div class="box-body">
                    
                    <h1 class="page-header text-center no-border font-size-40 font-weight-600"><span class="text-dark">Crypto Actions for project completion</span></h1>

                    <div class="row">

                        <div class="col-lg-6">
                        
                            <h5 class="text-dark">

                                <span class="">Total Tokens Yet to be Burned: </span> <span id="stakingBalToBeBurned">0 </span> {{$project->token_symbol}}
                            </h5>

                        </div>

                        <div class="col-lg-6">

                            <h5 class="text-dark">

                                <span class="">Total Staking Balance: </span> <span id="totalstakingBalance">0 </span> {{$project->token_symbol}}
                            </h5>

                        </div>

                        <hr>

                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-12">
                            <div class="text-center mt-15">

                                <div class="btn-sec">

                                    <div class="loader hide"></div>

                                    @if($project->pool_contract_address)

                                        @if($project->admin_burn_access && $project->admin_mint_access && $project->publish_status == PROJECT_PUBLISH_STATUS_OPENED)

                                            <!-- <button id="burnBtn" class="btn btn-dark text-uppercase">BURN STACK TOKENS</button>
                                           
                                            <button id="mintBtn" class="btn btn-dark text-uppercase">MINT UNSTACK TOKENS</button> -->
                                            

                                        @elseif($project->publish_status == PROJECT_PUBLISH_STATUS_CLOSED)

                                            @if($project->investors_settlement_status == NO)

                                                <button id="transferTokenToInvestor" class="btn btn-dark text-uppercase">Send Bal Tokens to Investors</button>

                                            @endif

                                            @if($project->investors_settlement_status == YES && $project->project_owner_settlement_status == NO)

                                                <button id="sendProjTokenBtn" class="btn btn-dark text-uppercase">Send Project Tokens to Investor</button>

                                            @endif 

                                            @if($project->project_owner_settlement_status == YES && $project->investors_settlement_status == YES)

                                                @if($project->admin_burn_access == ACCESS_GRANTED || $project->admin_mint_access == ACCESS_GRANTED)

                                                <button id="revokeAccessBtn" class="btn btn-dark text-uppercase">Revoke Access For Burn & Mint</button>

                                                @endif

                                            @endif

                                        @else

                                            <!-- @if($project->admin_mint_access == ACCESS_PENDING || $project->admin_burn_access == ACCESS_PENDING)

                                                <button id="grantAccessBtn" class="btn btn-dark text-uppercase">GRANT ACCESS FOR BURN & MINT</button>

                                            @endif -->

                                        @endif

                                    @else
                                        
                                        <button id="deployContract" class="btn btn-dark text-uppercase">DEPLOY NOW</button>

                                    @endif
                                    
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            
            </div>

        </div>

    </div> 

    <!-- Project details START -->

    <div class="row">

        <div class="col-lg-12">
            <h3 class="text-uppercase">{{tr('project_details')}}</h3>
        </div>
        
        <div class="col-lg-6 col-12">

            <div class="row">

                <div class="col-6">
                    <a class="box box-link-pop text-center bg-dark" href="javascript:void(0)">
                        <div class="box-body py-25">
                            <p class="font-size-20 text-yellow">
                                <strong>{{$project->total_tokens_formatted}}</strong>
                            </p>
                            <p class="font-weight-600">{{tr('total_tokens')}}</p>
                        </div>
                    </a>
                </div>

                <div class="col-6">
                    <a class="box box-link-pop bg-cyan text-center" href="javascript:void(0)">
                        <div class="box-body py-25">
                            <p class="font-size-20 text-white">
                                <strong>{{$project->allowed_tokens_formatted}}</strong>
                            </p>
                            <p class="font-weight-600">{{tr('allowed_tokens')}}</p>
                        </div>
                    </a>
                </div>

                <div class="col-md-12">

                    <div class="progress">
                        <div class="progress-bar progress-bar-warning progress-bar-striped" id="progress_token_percentage_width" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>

                    <div class="">

                        <p class="float-right"><span id="totalPurchasedProgresss">0</span> {{$project->token_symbol}} | {{$project->allowed_tokens}} {{$project->token_symbol}}</p>

                        <p class="float-left"> <span id="progress_token_percentage">0</span>%</p>

                    </div>

                </div>
            </div>
            
            <div class="box">
                
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover no-margin">
                            <tbody>
                                <tr>
                                    <td>{{tr('unique_id')}}</td>
                                    <td>{{$project->project_unique_id}}</td>
                                </tr>

                                <tr>
                                    <td>{{tr('username')}}</td>
                                    <td><a href="{{route('admin.users.view', ['user_id' => $project->user_id])}}"> {{$project->username ?: tr('not_available')}}</a></td>
                                </tr>
                                <tr>
                                    <td>{{tr('username')}}</td>
                                    <td><a href="{{route('admin.users.view', ['user_id' => $project->user_id])}}"> {{$project->username ?: tr('not_available')}}</a></td>
                                </tr>
                                <tr>
                                    <td>{{tr('contract_address')}}</td>
                                    <td>{{$project->contract_address}}</td>
                                </tr>
                                <tr>
                                    <td>{{tr('exchange_rate')}}</td>
                                    <td>1 {{Setting::get('currency')}} = {{$project->exchange_rate}} {{$project->token_symbol}}</td>
                                </tr>
                                <tr>
                                    <td>{{ tr('swap_rate') }} ({{tr('conversion_value')}})</td>
                                    <td>{{$project->exchange_rate}}</td>
                                </tr>
                                <tr>
                                    <td>{{tr('start_time')}}</td>
                                    <td>{{common_date($project->start_time, Auth::guard('admin')->user()->timezone)}}</td>
                                </tr>
                                <tr>
                                    <td>{{tr('end_time')}}</td>
                                    <td>{{common_date($project->end_time, Auth::guard('admin')->user()->timezone)}}</td>
                                </tr>

                                <tr>
                                    <td>{{tr('total_users_participated')}}</td>
                                    <td>
                                        <a class="btn btn-success btn-xs margin" href="{{ route('admin.invested_projects' , ['project_id' => $project->id] ) }}">
                                        {{$project->total_users_participated}}
                                         </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{tr('total_tokens_purchased')}}</td>
                                    <td><span id="total_tokens_purchased_html">{{$project->total_tokens_purchased}}</span> {{$project->token_symbol}}</td>
                                </tr>
                                <tr>
                                    <td>{{tr('status')}}</td>
                                    <td>
                                        @if($project->status == APPROVED)

                                            <span class="btn btn-success btn-sm">{{ tr('approved') }}</span>

                                        @else

                                            <span class="btn btn-warning btn-sm">{{ tr('declined') }}</span>

                                        @endif
                                    </td>
                                </tr>
                               
                                <tr>
                                    <td>{{tr('publish_status')}}</td>
                                    <td>

                                        <span class="btn btn-primary btn-sm">{{$project->publish_status_formatted}}</span>

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.box-body -->
             </div>
        </div>
        
        <div class="col-lg-6 col-12">

            <div class="row col-md-12">
                <div class="box bl-3 bg-dark border-default">
                    <div class="box-header">
                        <h4 class="box-title text-white">{{tr('pool_contract_address')}}</strong></h4>
                    </div>
                    <div class="box-body">
                        <h4><code><span class="text-blue">{{$project->pool_contract_address ?: "N/A"}}</span></code></h4>
                        <p>Note: Each project will have seperate pool contract. All the transactions will happen using this contract address.</p>
                    </div>
                </div>
            </div>

            <div class="row col-md-12">
                <div class="box bl-3 bg-dark border-default">
                    <div class="box-header">
                        <h4 class="box-title text-white">Project Owner Wallet Address</strong></h4>
                    </div>
                    <div class="box-body">
                        <h4><code><span class="text-blue">{{$project->from_wallet_address ?? "N/A"}}</span></code></h4>
                        <p>Note: Each project will have seperate pool contract. All the transactions will happen using this contract address.</p>
                    </div>
                </div>
            </div>

            <div class="row col-md-12">
                <div class="box bl-3 border-default">
                    <div class="box-header">
                        <h4 class="box-title">{{tr('description')}}</strong></h4>
                    </div>
                    <div class="box-body">
                        <p>{{$project->description}}</p>
                    </div>
                </div>
            </div>
            
        </div>
    
    </div>

    <!-- Project details END -->

<div class="row">
    <div class="col-md-12">

        <div class="box">

            <div class="box-header with-border">
                
                <h3 class="box-title">{{tr('investors')}}</h3>
            </div>

            <div class="box-body">

                <div class="callout bg-pale-secondary">
                    <h4>Notes:</h4>
                    <p>
                        <ul>
                            <li>All the Projects Investor will be displayed here with basic information. </li>
                        </ul>
                    </p>
                </div>

                <div class="table-responsive">
                    
                    <table id="example1" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                        
                        <thead>
                            
                            <tr>
                                <th>{{ tr('s_no') }}</th>
                                <th>{{ tr('user') }}</th>
                                <th>{{ tr('staked') }}</th>
                                <th>{{ tr('unstaked') }}</th>
                                <th>{{ tr('project_tokens_to_be_sent') }}</th>
                                <th>{{ tr('remaining_lc_tokens') }}</th>
                            </tr>

                        </thead>
                        <tbody>
                            
                            @foreach($project_stacks as $i => $project_stack)

                                <tr>
                                    
                                    <td>{{ $i+$project_stacks->firstItem() }}</td>

                                    <td class="white-space-nowrap">
                                        <a href="{{route('admin.users.view' , ['user_id' => $project_stack->user_id])}}" class="custom-a">
                                            {{$project_stack->user->name ?? tr('not_available')}}
                                        </a>

                                    </td>

                                    <td><span class="label label-default"> {{$project_stack->stacked_formatted}}</span></td>

                                    <td><span class="label label-default"> {{$project_stack->unstacked_formatted}}</span></td>

                                    <td></td>

                                    <td></td>
                                   

                                </tr>
                                
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="box-footer clearfix">
                    
                <div class="pull-right rd-flex">{{ $project_stacks->appends(request()->input())->links('pagination::bootstrap-4') }}</div>
            </div>

        </div>

    </div>
</div>

@endsection

@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/web3@latest/dist/web3.min.js"></script>

<script type="module">

    let loading = false;

    $(window).on('beforeunload', function(e) {
        var reload_value = undefined;
        if (loading) {
            e.preventDefault();
            reload_value = '';
        }
        return reload_value;
    });
 

    import Token from "{{asset('abis/Token.json')}}" assert { type: "json" };

    // import EthSwap from "{{asset('abis/EthSwap.json')}}" assert { type: "json" };

    import StakingPool from "{{asset('abis/StakingPool.json')}}" assert { type: "json" };

    let token;
    let account;
    let tokenBalance;
    let etherSwap;
    let stakingPool;
    let projectContractAddress = "{{$project->contract_address}}"; //0x25704ec20ca19909a6e17209ce4104d45c97df97
    let poolContractAddress = "{{$project->pool_contract_address}}"; //0x25704ec20ca19909a6e17209ce4104d45c97df97
    let projectOwnerAddress = "{{$project->from_wallet_address ?? "N/A"}}"; //0xf6Ae57ae312E16Df7e807da6B0c7132F337f8676

    let netID = 56;

    let chainIDhexacode = "0x38";

    let chainStatus = false;
    
    let totalTokensPurchased = "{{$project->total_tokens_purchased ?? 0.00}}";

    async function loadWeb3() {

        if (window.ethereum) {
            window.web3 = new Web3(window.ethereum);
            await window.ethereum.enable();
            loadBlockchainData();
        } else if (window.web3) {
            window.web3 = new Web3(window.web3.currentProvider);
            loadBlockchainData();
        } else {
            window.alert(
                "Non-Ethereum browser detected. You should consider trying MetaMask!"
            );
        }

        if(totalTokensPurchased != "0.00" || totalTokensPurchased != 0.00) {

            let totalTokensPurchasedToWei = window.web3.utils.fromWei(totalTokensPurchased, "Ether");

            let allowedTokens = "{{$project->allowed_tokens}}";

            allowedTokens = window.web3.utils.toWei(allowedTokens, "Ether");

            let progressPercentage = ((totalTokensPurchased/allowedTokens).toFixed(2) * 100);

            $('#progress_token_percentage_width').css('width', progressPercentage+"%");

            $('#progress_token_percentage').html(progressPercentage);

            $('#totalPurchasedProgresss').html(totalTokensPurchasedToWei);
        }
    
    }

    async function checkConnection(){

        let web3 = window.ethereum;

        // Check if browser is running Metamask
        console.log("checking connection");

        if (window.ethereum) {

          web3 = new Web3(window.ethereum);

        } else if (window.web3) {

          web3 = new Web3(window.web3.currentProvider);

        }

        try {
          const networkId = await web3.eth.net.getId();

          console.log("Networkid", networkId);

          if (networkId === Number(netID)) {

            await web3.eth.getAccounts().then(async (response) => {

              if (response.length > 0) {

                console.log("effect save");

                if(totalTokensPurchased != "0.00" || totalTokensPurchased != 0.00) {

                    let totalTokensPurchasedToWei = web3.utils.fromWei(totalTokensPurchased, "Ether");

                    let allowedTokens = "{{$project->allowed_tokens}}";

                    allowedTokens = web3.utils.toWei(allowedTokens, "Ether");

                    let progressPercentage = ((totalTokensPurchased/allowedTokens).toFixed(2) * 100);

                    $('#progress_token_percentage_width').css('width', progressPercentage+"%");

                    $('#progress_token_percentage').html(progressPercentage);

                    $('#totalPurchasedProgresss').html(totalTokensPurchasedToWei);
                }

                loadBlockchainData();

              } 
              else {

                await window.ethereum.enable();

                loadBlockchainData();

              }
            });
          } else {

            console.log("change network");

            changeNetwork()
          }
        } catch (e) {

          console.log("error"+ e);
        }
    };

    async function changeNetwork(){

        // MetaMask injects the global API into window.ethereum
        if (window.ethereum) {

          try {

            // check if the chain to connect to is installed
            await window.ethereum.request({
              method: "wallet_switchEthereumChain",
              params: [{ chainId: chainIDhexacode }], // chainId must be in hexadecimal numbers
            });

            location.reload();

          } catch (error) {
            // This error code indicates that the chain has not been added to MetaMask
            // if it is not, then install it into the user MetaMask
            if (error.code === 4902) {

              try {

                await window.ethereum.request({
                  method: "wallet_addEthereumChain",
                  params: [
                    {
                      chainId: "0x38",
                      rpcUrls: ["https://bsc-dataseed1.ninicoin.io"],
                      chainName: "Smart Chain - MainNet",
                      nativeCurrency: {
                        name: "Binance",
                        symbol: "BNB", // 2-6 characters long
                        decimals: 18,
                      },
                      blockExplorerUrls: ["https://.bscscan.com"],
                    },
                  ],
                });

                await window.ethereum.enable();

                console.log("Etherum enabled");

                loadBlockchainData();

              } catch (addError) {

                console.error(addError);
              }
            }
            console.error(error);
          }
        } else {
          // if no window.ethereum then MetaMask is not installed
          alert(
            "MetaMask is not installed. Please consider installing it: https://metamask.io/download.html"
          );
        }
    };

    async function checkAccountChange() {

        window.ethereum.on("accountsChanged", async function (accounts) {

          const web3 = window.web3;

          const network = await web3.eth.net.getId();

          console.log("networ", network);

          if (network !== Number(netID)) {

            //must be on mainnet or Testnet
            console.log("Only this");

            loadBlockchainData();

            changeNetwork();

          } else {

            //Do this check to detect if the user disconnected their wallet from the Dapp
            if (accounts && accounts[0]) loadBlockchainData();

            else {

              loadBlockchainData();

            }

          }
        });

        window.ethereum.on("chainChanged", (chainId) => {

          console.log("chain changed. ");

          chainStatus = true;
         
        });
    };

    async function loadBlockchainData() {

        const web3 = new Web3(window.ethereum);

        const accounts = await web3.eth.getAccounts();

        console.log("Accounts", accounts[0]);

        account = accounts[0];

        web3.eth.getBlockNumber(function(error, result) {
            console.log("block number", result)
        })
        const ethBalance = await web3.eth.getBalance(accounts[0]);
        console.log("Ether balance", web3.utils.fromWei(ethBalance, "Ether"));

        // Load Token
        const networkId = await web3.eth.net.getId();
        console.log("Network is", networkId);
        const tokenData = Token.networks[networkId];
        console.log("tokendata", tokenData);
        // if (tokenData) {
            const tempToken = new web3.eth.Contract(Token.abi, "0xe9e7CEA3DedcA5984780Bafc599bD69ADd087D56");
            token = tempToken;
            let tempTokenBalance = await tempToken.methods.balanceOf(account).call();
            tokenBalance = tempTokenBalance.toString();
        // }

        console.log("pool_contract","{{$project->pool_contract_address}}");

        if(poolContractAddress != null){

            loadStakingPoolContract();
        }

        // this.setState({ loading: false });

        if(totalTokensPurchased != "0.00" || totalTokensPurchased != 0.00) {

            let totalTokensPurchasedToWei = web3.utils.fromWei(totalTokensPurchased.toString(), "Ether");

            $('#total_tokens_purchased_html').html(totalTokensPurchasedToWei);
 
        }
    
    }

    async function loadStakingPoolContract(){

        try {

            // Load staking pool contract
            const web3 = new Web3(window.ethereum);
            
            const networkId = await web3.eth.net.getId();
            
            const stakingPoolData = StakingPool.networks[networkId];

            if (stakingPoolData) {

                const tempStakingPool = new web3.eth.Contract(
                    StakingPool.abi,
                    // stakingPoolData.address // @todo Load pool_contract_address here 
                    poolContractAddress // @todo Load pool_contract_address here 
                );
                stakingPool = tempStakingPool;

                console.log("name", await tempStakingPool.methods.name().call());

                let stakingBalance = await tempStakingPool.methods
                    .stakingBalance(account)
                    .call();
                let totalstakingBalance = await tempStakingPool.methods
                    .totalStakeBalance()
                    .call();

                $('#totalstakingBalance').html(web3.utils.fromWei(totalstakingBalance.toString(), "Ether"));

                //stakingBalToBeBurned
                let stakingBalToBeBurned = await tempStakingPool.methods
                    .stakingBalToBeBurned()
                    .call();

                console.log("Total stakingBalToBeBurned ", stakingBalToBeBurned.toString());

                $('#stakingBalToBeBurned').html(stakingBalToBeBurned.toString());

                $('#stakingBalToBeBurned').html(web3.utils.fromWei(stakingBalToBeBurned.toString(), "Ether"));

                // this.setState({ stakingBalToBeBurned: stakingBalToBeBurned.toString() });

                // unstakers
                let unStakedUserDetails = await tempStakingPool.methods
                    .unStakedUserDetails(account)
                    .call();

                console.log(" unStakedUserDetails ", unStakedUserDetails.toString());

                const check1 = totalstakingBalance.toString();

                console.log("Total staking balance", totalstakingBalance.toString());
                
                console.log("Staking poll", tempStakingPool._address);

            } else {

                window.alert("stakingPool contract not deployed to detected network.");
            }

            loading = false;

        }catch (error) {

            console.log("addError"+error);
        }
    
    }

    async function deployContract() {

        loading = true;

        $( ".loader" ).removeClass( "hide" );

        $( ".btn" ).attr('disabled', true);

        const web3 = new Web3(window.ethereum);

        const stakingPoolNewContract = new web3.eth.Contract(StakingPool.abi);

        let projectName = "{{$project->name}}"+ "Staking Pool";

        console.log('token_address', token._address)

        const res = await stakingPoolNewContract
            .deploy({
                data: StakingPool.bytecode,
                arguments: [
                    token._address,
                    // "Project XY token Pool", // @todo project name + "Staking Pool"
                    projectName, // @todo project name + "Staking Pool"
                ],
            })
            .send({
                    from: account,
                    gas: 5000000,
                    gasPrice: 25000000000,
                },
                function(error, transactionHash) {

                    if (!transactionHash) {

                        loading = false;
                        $( ".loader" ).addClass( "hide" );
                        $( ".btn" ).attr('disabled', false);
                        location.reload();
                    }

                    console.log("Txt", transactionHash);
                }
            )
            .on("confirmation", function(confirmationNumber, receipt) {

                console.log("con", confirmationNumber);

            })
            .then(async function(newContractInstance) {

                console.log(
                    "name",
                    await newContractInstance.methods.name.call().toString()
                );

                console.log(newContractInstance.options.address); // @todo save this as pool_contract_address instance with the new contract address

                savePoolContractAddress(newContractInstance.options.address);

                loadStakingPoolContract();
            })
            .on("error", (error) => {

                loading = false;
                $( ".loader" ).addClass( "hide" );
                $( ".btn" ).attr('disabled', false);
            });

        console.log("Res", res);
    
    }

    async function savePoolContractAddress(pool_contract_address) {

        let projectId = "{{$project->project_id}}";

        $.ajax({
            type : 'post',
            url : "{{route('admin.projects_pool_contract_save')}}",
            data : {"_token": "{{ csrf_token() }}",'project_id': projectId, 'pool_contract_address': pool_contract_address},
            success : function(response) {
                console.log("pool_contract_address success")
            },
            error : function(data) {
                console.log("pool_contract_address error")
            }

        });

        loading = false;
        $( ".loader" ).addClass( "hide" );
        $( ".btn" ).attr('disabled', false);
        location.reload();


        return false;
    
    };

    // Grant Minter access
    async function grantAccess() {

        loading = true;
        $( ".loader" ).removeClass( "hide" );
        $( ".btn" ).attr('disabled', true);

        let minterAddress = poolContractAddress;

        token.methods
        .grandAccessRole(minterAddress)
        .send({ from: account })
        .on("transactionHash", (hash) => {
            console.log("Trx", hash);
            burnAccessUpdate();
        })
        .on("error", (error) => {
            loading = false;
            $( ".loader" ).addClass( "hide" );
            $( ".btn" ).attr('disabled', false);
        });
    };

    // Grant burner access
    async function grantBurnAccess() {
        
        let burnerAddress = poolContractAddress;

        console.log(poolContractAddress);

        token.methods
        .grandBurnerRole(burnerAddress)
        .send({ from: account })
        .on("transactionHash", (hash) => {
            console.log("Trx", hash);
            burnAccessUpdate();
        });
    };

    async function burnStakedToken() {
        loading = true;
        $( ".loader" ).removeClass( "hide" );
        $( ".btn" ).attr('disabled', true);
        stakingPool.methods
            .burnStakedTokens(stakingPool._address)
            .send({
                from: account
            })
            .on("transactionHash", (hash) => {
                console.log("transaction Details", hash);
                // this.setState({ loading: false });
            })
            .on("error", (error) => {
                loading = false;
                $( ".loader" ).addClass( "hide" );
                $( ".btn" ).attr('disabled', false);
            })
            .then((result) => {
                loading = false;
                $( ".loader" ).addClass( "hide" );
                $( ".btn" ).attr('disabled', false);
                location.reload();
            });
    };

    async function mintUnstakedToken() {
        loading = true;
        $( ".loader" ).removeClass( "hide" );
        $( ".btn" ).attr('disabled', true);
        stakingPool.methods
            .mintUnstakedTokens()
            .send({
                from: account
            })
            .on("transactionHash", (hash) => {
                console.log("transaction Details", hash);
                // this.setState({ loading: false });
            })
            .on("error", (error) => {
                loading = false;
                $( ".loader" ).addClass( "hide" );
                $( ".btn" ).attr('disabled', false);
            })
            .then((result) => {
                loading = false;
                $( ".loader" ).addClass( "hide" );
                $( ".btn" ).attr('disabled', false);
                location.reload();
            });
    };

    // allowedTokens = Project allowed @todo 

    async function transferTokenToInvestor() {

        const web3 = new Web3(window.ethereum);

        loading = true;
        $( ".loader" ).removeClass( "hide" );
        $( ".btn" ).attr('disabled', true);

        let allowedTokens = "{{$project->allowed_tokens}}";

        console.log("allowedTokens"+allowedTokens);

        // Project _projectTokenPrice
        let _projectTokenPrice;

        // let exchangeRate = "1"; // @todo project exchange_rate load here
        let exchangeRate = "{{$project->exchange_rate}}"; // @todo project exchange_rate load here
        
        console.log("exchangeRate"+exchangeRate);

        _projectTokenPrice = exchangeRate;
       
        let calculatedProjectToken = allowedTokens * _projectTokenPrice; 

        console.log("calculatedProjectToken 1st"+calculatedProjectToken);

        let projectTotal = calculatedProjectToken.toString();

        calculatedProjectToken = web3.utils.toWei(projectTotal, "Ether");

        console.log("calculatedProjectToken 2nd"+projectTotal);

        // Decimal
        const decimals = web3.utils.toBN(18);

        console.log("decimals"+decimals);

        console.log("number calculatedProjectToken"+ Number(calculatedProjectToken));

        let tokenHex = Number(calculatedProjectToken).toString(16);

        console.log('tokenHex'+ tokenHex)

        // Amount of token
        const tokenAmount = web3.utils.toBN(tokenHex);

        console.log("tokenAmount"+tokenAmount);

        // Amount as Hex - contract.methods.transfer(toAddress, tokenAmountHex).encodeABI();
        const tokenAmountHex = '0x' + tokenAmount.toString('hex');

        console.log("tokenAmountHex"+tokenAmountHex);

        console.log("projectOwnerAddress"+projectOwnerAddress);

        await stakingPool.methods
            .transferTokenToInvestor(
                tokenAmountHex,
                // this.state.projectOwnerAddress // @todo project owner wallet address
                projectOwnerAddress // @todo project owner wallet address
            )
            .send({
                from: account
            })
            .on("receipt", async (receipt) => {
                console.log("Trx", receipt.transactionHash);
                investorsSettlementStatusUpdate();
                loading = false;
                $( ".loader" ).addClass( "hide" );
                $( ".btn" ).attr('disabled', false);
                location.reload();
            })
            .on("error", (error) => {
                loading = false;
                $( ".loader" ).addClass( "hide" );
                $( ".btn" ).attr('disabled', false);
            });
   
    };

    async function sendProjToken() {

        loading = true;
        $( ".loader" ).removeClass( "hide" );
        $( ".btn" ).attr('disabled', true);

        console.log("sendProjToken start");

        // Decimal
        const web3 = new Web3(window.ethereum);
        
        const decimals = web3.utils.toBN(18);

        let projectTokenAddress = projectContractAddress;

        console.log("poolContractAddress"+poolContractAddress);

        let exchangeRate = "{{$project->exchange_rate}}"; // @todo project exchange_rate load here
        
        console.log("exchangeRate"+exchangeRate);

        let projectTokenPrice = web3.utils.toWei(exchangeRate, "Ether");

        projectTokenPrice = web3.utils.toBN(parseInt(projectTokenPrice));

        projectTokenPrice = '0x' + projectTokenPrice.toString('hex');

        console.log("projectTokenPrice"+projectTokenPrice);

        // Load the Project Token.
        
        const networkId = await web3.eth.net.getId();

        const projectToken = Token.networks[networkId];
        
        if (projectToken) {

            const projToken = new web3.eth.Contract(
                Token.abi,
                projectTokenAddress
            );
            
            let totalAllocated = 0;

            totalAllocated = await stakingPool.methods
                .totalProjToken()
                .call();

            totalAllocated = web3.utils.fromWei(totalAllocated, "Ether");

            console.log("totalAllocated FromWei"+totalAllocated);

            totalAllocated = Number(totalAllocated) / Number(exchangeRate);

            console.log("totalAllocated"+totalAllocated);

            let decimal = await projToken.methods.decimals().call();
            
            let finalBal = totalAllocated * 10 ** decimal;

            // finalBal = finalBal.toString();

            console.log("finalBal"+finalBal);

            // Amount of token
            const tokenAmount = web3.utils.toBN(parseInt(totalAllocated));

            console.log("tokenAmount"+tokenAmount);

            // Amount as Hex - contract.methods.transfer(toAddress, tokenAmountHex).encodeABI();
            const tokenAmountHex = '0x' + tokenAmount.mul(web3.utils.toBN(10).pow(decimals)).toString('hex');

            console.log("tokenAmountHex"+tokenAmountHex);

            await projToken.methods
                .approve(poolContractAddress, tokenAmountHex)
                .send({
                    from: account
                })
                .on("receipt", async (receipt) => {
                await stakingPool.methods
                    .sendProjTokenInvestor(projectTokenAddress, projectTokenPrice)
                    .send({
                        from: account
                    })
                    .on("receipt", async (receipt) => {
                        console.log("Trx", receipt.transactionHash);
                        projectOwnerSettlementStatusUpdate();
                        loading = false;
                        $( ".loader" ).addClass( "hide" );
                        $( ".btn" ).attr('disabled', false);
                        location.reload();
                    });
                })
                .on("error", (error) => {
                    loading = false;
                    $( ".loader" ).addClass( "hide" );
                    $( ".btn" ).attr('disabled', false);
                })
        
        }
    
    }


    // Revoke access - mint - MINTER_ROLE, burn - BURNER_ROLE 
    async function revokeGrantedAccess(accessName) {

        loading = true;
        $( ".loader" ).removeClass( "hide" );
        $( ".btn" ).attr('disabled', true);

        let address = poolContractAddress;

        token.methods
        .revokeAccess(accessName, address)
        .send({ from: account })
        .on("transactionHash", (hash) => {
            console.log("Trx", hash);
            revokeAccessUpdate(accessName);
        })
        .on("error", (error) => {
            loading = false;
            $( ".loader" ).addClass( "hide" );
            $( ".btn" ).attr('disabled', false);
            window.location.reload();
        })
        .then((result) => {
            loading = false;
            $( ".loader" ).addClass( "hide" );
            $( ".btn" ).attr('disabled', false);
            window.location.reload();
        });
    
    };

    // API's START

    async function mintAccessUpdate() {

        let projectId = "{{$project->project_id}}";

        $.ajax({
            type : 'post',
            url : "{{route('admin.projects_mint_access_update')}}",
            data : {"_token": "{{ csrf_token() }}",'project_id': projectId, 'status': 1},
            success : function(response) {
                console.log("mintAccessUpdate success")

                // window.location.reload();

            },
            error : function(data) {
                console.log("mintAccessUpdate error")
            }

        });

        return false;
    
    }

    async function burnAccessUpdate() {
        
        let projectId = "{{$project->project_id}}";

        $.ajax({
            type : 'post',
            url : "{{route('admin.projects_burn_access_update')}}",
            data : {"_token": "{{ csrf_token() }}",'project_id': projectId, 'status': 1},
            success : function(response) {
                console.log("burnAccessUpdate success")
                // window.location.reload();
                loading = false;
                $( ".loader" ).addClass( "hide" );
                $( ".btn" ).attr('disabled', false);
                location.reload();

            },
            error : function(data) {
                console.log("burnAccessUpdate error")
                loading = false;
                $( ".loader" ).addClass( "hide" );
                $( ".btn" ).attr('disabled', false);
            }

        });

        return false;
    
    }

    async function revokeAccessUpdate(type) {

        let projectId = "{{$project->project_id}}";

        $.ajax({
            type : 'post',
            url : "{{route('admin.projects_revoke_access')}}",
            data : {"_token": "{{ csrf_token() }}",'project_id': projectId, "type": type},
            success : function(response) {
                console.log("revokeAccessUpdate success")

                // window.location.reload();
            },
            error : function(data) {
                console.log("revokeAccessUpdate error")
            }

        });

        return false;
    
    }

    async function investorsSettlementStatusUpdate(type) {

        let projectId = "{{$project->project_id}}";

        $.ajax({
            type : 'post',
            url : "{{route('admin.projects_investors_settlement_status')}}",
            data : {"_token": "{{ csrf_token() }}",'project_id': projectId},
            success : function(response) {
                console.log("investorsSettlementStatusUpdate success")
                loading = false;
                $( ".loader" ).addClass( "hide" );
                $( ".btn" ).attr('disabled', false);
                location.reload();
                // window.location.reload();
            },
            error : function(data) {
                console.log("investorsSettlementStatusUpdate error")
                loading = false;
                $( ".loader" ).addClass( "hide" );
                $( ".btn" ).attr('disabled', false);
            }

        });
        loading = false;
        $( ".loader" ).addClass( "hide" );

        return false;
    
    }

    async function projectOwnerSettlementStatusUpdate(type) {

        let projectId = "{{$project->project_id}}";

        $.ajax({
            type : 'post',
            url : "{{route('admin.project_owner_settlement_status')}}",
            data : {"_token": "{{ csrf_token() }}",'project_id': projectId},
            success : function(response) {
                console.log("projectOwnerSettlementStatusUpdate success")
                loading = false;
                $( ".loader" ).addClass( "hide" );
                $( ".btn" ).attr('disabled', false);
                location.reload();
                // window.location.reload();
            },
            error : function(data) {
                console.log("projectOwnerSettlementStatusUpdate error")
                loading = false;
                $( ".loader" ).addClass( "hide" );
                $( ".btn" ).attr('disabled', false);
            }

        });
        // loading = false;
        // $( ".loader" ).addClass( "hide" );

        return false;
    
    }

    checkConnection();

    if(document.getElementById("deployContract") != null) {

        document.querySelector('#deployContract').addEventListener('click', function() {
            deployContract();
        });
    }

    if(document.getElementById("grantAccessBtn") != null) {

        document.querySelector('#grantAccessBtn').addEventListener('click', function() {
            grantAccess();
        });

    }

    if(document.getElementById("burnBtn") != null) {

        document.querySelector('#burnBtn').addEventListener('click', function() {
            burnStakedToken();
        });

    }

    if(document.getElementById("mintBtn") != null) {

        document.querySelector('#mintBtn').addEventListener('click', function() {
            mintUnstakedToken();
        });

    }

    if(document.getElementById("transferTokenToInvestor") != null) {

        document.querySelector('#transferTokenToInvestor').addEventListener('click', function() {
            transferTokenToInvestor();
        });

    }
    
    if(document.getElementById("sendProjTokenBtn") != null) {

        document.querySelector('#sendProjTokenBtn').addEventListener('click', function() {
            sendProjToken();
        });

    }

    if(document.getElementById("revokeAccessBtn") != null) {

        document.querySelector('#revokeAccessBtn').addEventListener('click', function() {
            revokeGrantedAccess('MINTER_BURNER_ROLE');
        });

    }


</script>

@endsection
