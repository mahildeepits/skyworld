@extends('layout.main')
@section('content')
    <style>
        .fontsize-14 {
            font-size: 16px;
        }

        .fontsize-24px {
            font-size: 35px;
        }

        .card-about-small {
            border-radius: 10px;
            flex: 0 0 calc(33.333% - 10px);
            max-width: calc(33.333% - 10px);
            padding: 6px !important;
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(0, 0, 0, 0.4);
        }

        .card-bussiness-modal {
            border-radius: 10px;
            flex: 0 0 calc(33.333% - 10px);
            max-width: calc(33.333% - 10px);
            padding: 6px !important;
            border: 1px solid rgba(255, 255, 255, 0.4);
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        @media (max-width: 576px) {
            .card-about-small {
                flex: 0 0 calc(50% - 10px);
                max-width: calc(50% - 10px);
            }

            .card-bussiness-modal {
                flex: 0 0 calc(100% - 10px);
                max-width: calc(100% - 10px);
            }
        }

        .bg-light-blue {
            background: rgb(67, 191, 225, 0.1) !important;
        }
        .focus-border{
            padding: 5px 25px !important;
            border: 5px solid #43bfe1;
            border-radius: 30px;
            width: 250px;
        }
        .center-focus-boxes{
            text-align: -webkit-center;
        }
        .border-main{
            border:3px solid skyblue;
        }
    </style>
    @php
        $user = authUser();
    @endphp
    <x-page-breadcrumb :currentPage="'About Us'" />
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 p-0">
                            <h2 class="text-main px-4 py-2 bg-light-blue">About US</h2>
                        </div>
                        <div class="col-md-6" style="padding-left: 0px!important;padding-right:0px!important;">
                            <div class="px-4 mx-2">
                                <img src="{{ asset('images/54.png') }}" alt="" width="35%">
                                <p class="px-3">has evolved into a successful integrated marketing communications (IMC)
                                    firm,
                                    offering a wide range of quality services designed to meet the diverse needs of clients
                                </p>
                            </div>
                            <div class="px-5 py-3" style="background: rgb(67, 191, 225, 0.1);">
                                <p class=""><b>Our expertise spans covering all areas of strategic
                                        communicationsincluding</b>
                                </p>
                                <div class="d-flex flex-wrap gap-2 mt-3">
                                    <div class="card p-0 card-about-small">
                                        <div class="card-body text-center p-2 align-content-center">
                                            <i class='bx bx-car-key fontsize-24px'></i>
                                            <h6 class="fontsize-14">Strategic Consulting</h6>
                                        </div>
                                    </div>
                                    <div class="card p-0 card-about-small">
                                        <div class="card-body text-center p-2 align-content-center">
                                            <i class='bx bx-cuboid fontsize-24px'></i>
                                            <h6 class="fontsize-14">Market Research and Insights</h6>
                                        </div>
                                    </div>
                                    <div class="card p-0 card-about-small">
                                        <div class="card-body text-center p-2 align-content-center">
                                            <i class='bx bx-volume-full fontsize-24px'></i>
                                            <h6 class="fontsize-14">Public Relations and Publicity</h6>
                                        </div>
                                    </div>
                                    <div class="card p-0 card-about-small">
                                        <div class="card-body text-center p-2 align-content-center">
                                            <i class='bx bx-cognition fontsize-24px'></i>
                                            <h6 class="fontsize-14">Media Planning</h6>
                                        </div>
                                    </div>
                                    <div class="card p-0 card-about-small">
                                        <div class="card-body text-center p-2 align-content-center">
                                            <i class='bx bx-dollar-circle fontsize-24px'></i>
                                            <h6 class="fontsize-14">Influencer Marketing</h6>
                                        </div>
                                    </div>
                                    <div class="card p-0 card-about-small">
                                        <div class="card-body text-center p-2 align-content-center">
                                            <i class='bx bx-shield-alt-2 fontsize-24px'></i>
                                            <h6 class="fontsize-14">Crisis Management</h6>
                                        </div>
                                    </div>
                                </div>
                                <p>
                                    This comprehensive capability makes us the best choice for clients seeking
                                    unified solutions to their marketing and communication challenges.
                                </p>
                            </div>

                        </div>
                        <div class="col-md-6 px-4 py-2" style="">
                            <div class="card" style="border-radius:10px; box-shadow: -1rem 1rem 4rem rgba(0, 0, 0, 0.5);">
                                <div class="card-img-top px-3 pt-3">
                                    <img src="{{ asset('images/aboutbg1.jpg') }}" class="img-thumbnail rounded"
                                        width="100%" alt="Card image cap">
                                </div>
                                <div class="card-body">
                                    <span class="border-main"
                                        style="width:20%; display: inline-block; border-radius: 10px;"></span>
                                    <p class="card-text pb-3"><b>In today’s highly competitive digital world, effective
                                            reputation
                                            and
                                            crisis
                                            management are crucial for a company’s success. Positive reviews can play
                                            apivotal
                                            role
                                            in shaping a company’s long-term success, stability, and public
                                            perception, fostering customer loyalty. For many consumers, online reviews
                                            arethe
                                            deciding factor when choosing between products or services. A robust
                                            collection of positive feedback can significantly impact purchasing decisions,
                                            drive
                                            increased traffic, and boost conversion rates. By proactively
                                            managingandenhancing
                                            their
                                            reputation, businesses can establish themselves as reliable andtrustworthy,
                                            distinguishing themselves in a crowded marketplace</b>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row py-4">
                        <div class="col-md-12 p-0">
                            <h2 class="text-main px-4 py-2 bg-light-blue">Bussiness Model</h2>
                            <p class="fontsize-14 px-4">
                                <b>BITTICO</b> has successfully collaborated with many prominent and influential clients
                                such as McDonald’s
                                Korea,
                                Sofitel Ambassador and Seal is now attracting a growing number of business owner and
                                enterpreneurs eager to collaborate. As a result, BITTICO is actively looking for more
                                freelancer
                                marketers and members to join their growing network
                            </p>
                        </div>

                    </div>
                    <div class="row" style="background:#43bfe1;">
                        <div class="col-md-8 align-content-center">
                            <div class="d-flex flex-wrap justify-content-center gap-2 mt-3">
                                <div class="card p-0 card-bussiness-modal">
                                    <div class="card-body p-2 ">
                                        <p class="p-2">All of BITTICO’ clients will pay for
                                            the services
                                            provided by BITTICO.</p>
                                    </div>
                                </div>
                                <div class="card p-0 card-bussiness-modal">
                                    <div class="card-body p-2">
                                        <p class=" p-2">In turn, BITTICO distributes the
                                            earnings to both the agent
                                            members who fulfill the orders and
                                            the Public Relations Department, thereby strengthening our
                                            partnerships.
                                        </p>
                                        @php
                                        $images_array = [
                                            'about-1.jpg',
                                            'about-2.jpg',
                                            'about-3.jpg',
                                            'about-4.jpg',
                                            'about-5.png',
                                            'about-6.png',
                                            'about-7.png',
                                            'about-8.png',
                                            'about-9.png',
                                            'about-10.png',
                                            'about-11.png',
                                            'about-12.png',
                                            'about-13.png',
                                            'about-14.png',
                                            'about-15.png',
                                            'about-16.png',
                                            'about-17.png',
                                            'about-18.png',
                                        ];
                                        @endphp
                                        <div class="d-flex gap-1 flex-wrap justify-content-start">
                                            @foreach ($images_array as $img_name)
                                            <div style="width:23%; background: white; border-radius: 5px;">
                                                <img src="{{ asset('images/'.$img_name) }}" class="rounded" alt=""
                                                width="100%">
                                            </div>
                                            @endforeach
                                            {{-- <img src="{{ asset('images/about-2.jpg') }}" class="rounded" alt=""
                                                width="25%">
                                            <img src="{{ asset('images/about-3.jpg') }}" class="rounded" alt=""
                                                width="25%">
                                            <img src="{{ asset('images/about-4.jpg') }}" class="rounded" alt=""
                                                width="25%"> --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="card p-0 card-bussiness-modal">
                                    <div class="card-body p-2 ">
                                        <p class="p-2">BITTICO is dedicated to
                                            creating a mutually
                                            beneficial situation for all
                                            parties, leading to greater
                                            accomplishments in the
                                            future.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <p class="p-2 mx-3 text-white" style="border-left:4px solid white;">
                                    Furthermore, any enterprise in need of BITTICO’s services can
                                    express their interest in a partnership at www.bittico.com, under
                                    the collaboration section. Both individuals and organizations can
                                    make requests based on their needs
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="my-3">
                                <img src="{{ asset('images/business-model.jpg') }}"
                                    style="border-radius:10px; box-shadow: -1rem 1rem 4rem rgba(0, 0, 0, 0.5);"
                                    width="100%" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="row py-2 mt-4">
                        <div class="col-md-12 p-0">
                            <h2 class="text-main px-4 py-2 bg-light-blue">Company Profile</h2>
                            <div class="px-4 py-2">
                                <h6>
                                    BITTICO PARTNERS provides public relations and marketing services worldwide
                                </h6>
                                <p>
                                    We offer inbound/outbound public relations services to client companies that wish to
                                    engage in domestic and overseas public relations by establishing strategic networks
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row px-4">
                        <div class="col-md-4 py-3">
                            <div class="center-focus-boxes">
                                <div style="max-width: 250px;">
                                    <img src="{{ asset('images/about-focus-1.jpg') }}" alt=""
                                    class="rounded-circle border-main" width="100%" >    
                                </div>
                                <h6 class="mt-3 focus-border">
                                    Strategic Consulting
                                </h6>
                                <p class="px-5">
                                    We provide strategic advice on brand positioning, marketing strategies, and communication
                                    plans, empowering our clients to create impactful
                                    marketing campaigns that are closely aligned with
                                    their business objectives.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 py-3">
                            <div class="center-focus-boxes">
                                <div style="max-width: 250px;">
                                    <img src="{{ asset('images/about-focus-2.jpg') }}" alt=""
                                    class="rounded-circle border-main " width="100%">    
                                </div>
                                <h6 class="mt-3 focus-border">
                                    Public Relations
                                </h6>
                                <p class="px-5">
                                    We manage media relationships and develop
                                    strategies to strengthen and elevate a
                                    brand’s reputation. By enhancing credibility
                                    and trust, we drive conversions and help our
                                    clients thrive in a competitive market.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 py-3">
                           <div class="center-focus-boxes">
                                <div style="max-width: 250px;">
                                    <img src="{{ asset('images/about-focus-3.jpg') }}" alt=""
                                    class="rounded-circle border-main " width="100%">    
                                </div>
                                <h6 class="mt-3 focus-border">
                                    Crisis management
                                </h6>
                                <p class="px-5">
                                    We offering immediate strategic
                                    communication support during a crisis tomitigate damage, control the narrative, andprotect
                                    the brand’s reputation.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>@endsection
