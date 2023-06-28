@extends('layouts.app')

    @section('title', 'Admin Form Login')

    @section('content')

    <div class="container">
    <div class="conditions mb-5">
        <div>
        <h3>CONDITIONS OF USE</h3>
        <br><br>
        Welcome to our online portal! quizzes.ltd and its associates provide their services to you subject to the following conditions. If you visit or shop within this website, you accept these conditions. Please read them carefully. ​
        <br><br>
        PRIVACY
        <br><br>
        Please review our Privacy Notice, which also governs your visit to our website, to understand our practices.
        <br><br>
        ELECTRONIC COMMUNICATIONS
        <br><br>
        When you visit quizzes.ltd or send e-mails to us, you are communicating with us electronically. You consent to receive communications from us electronically. We will communicate with you by e-mail or by posting notices on this site. You agree that all agreements, notices, disclosures and other communications that we provide to you electronically satisfy any legal requirement that such communications be in writing.
        <br><br>
        COPYRIGHT
        <br><br>
        All content included on this site, such as text, graphics, logos, button icons, images, audio clips, digital downloads, data compilations, and software, is the property of quizzes.ltd or its content suppliers and protected by international copyright laws. The compilation of all content on this site is the exclusive property of quizzes.ltd, with copyright authorship for this collection by quizzes.ltd, and protected by international copyright laws.
        <br><br>
        TRADE MARKS
        <br><br>
        quizzes.ltd trademarks and trade dress may not be used in connection with any product or service that is not quizzes.ltd, in any manner that is likely to cause confusion among customers, or in any manner that disparages or discredits quizzes.ltd. All other trademarks not owned by quizzes.ltd or its subsidiaries that appear on this site are the property of their respective owners, who may or may not be affiliated with, connected to, or sponsored by quizzes.ltd or its subsidiaries.
        <br><br>
        LICENSE AND SITE ACCESS
        <br><br>
        quizzes.ltd grants you a limited license to access and make personal use of this site and not to download (other than page caching) or modify it, or any portion of it, except with express written consent of quizzes.ltd. This license does not include any resale or commercial use of this site or its contents: any collection and use of any product listings, descriptions, or prices: any derivative use of this site or its contents: any downloading or copying of account information for the benefit of another merchant: or any use of data mining, robots, or similar data gathering and extraction tools. This site or any portion of this site may not be reproduced, duplicated, copied, sold, resold, visited, or otherwise exploited for any commercial purpose without express written consent of quizzes.ltd. You may not frame or utilize framing techniques to enclose any trademark, logo, or other proprietary information (including images, text, page layout, or form) of quizzes.ltd and our associates without express written consent. You may not use any meta tags or any other "hidden text" utilizing quizzes.ltd name or trademarks without the express written consent of quizzes.ltd. Any unauthorized use terminates the permission or license granted by quizzes.ltd. You are granted a limited, revocable, and nonexclusive right to create a hyperlink to the home page of quizzes.ltd so long as the link does not portray quizzes.ltd, its associates, or their products or services in a false, misleading, derogatory, or otherwise offensive matter. You may not use any quizzes.ltd logo or other proprietary graphic or trademark as part of the link without express written permission.
        <br><br>
        YOUR MEMBERSHIP ACCOUNT
        <br><br>
        If you use this site, you are responsible for maintaining the confidentiality of your account and password and for restricting access to your computer, and you agree to accept responsibility for all activities that occur under your account or password. If you are under 18, you may use our website only with involvement of a parent or guardian. quizzes.ltd and its associates reserve the right to refuse service, terminate accounts, remove or edit content, or cancel orders in their sole discretion.
        <br><br>
        REVIEWS, COMMENTS, EMAILS, AND OTHER CONTENT
        <br><br>
        Visitors may post reviews, comments, and other content: and submit suggestions, ideas, comments, questions, or other information, so long as the content is not illegal, obscene, threatening, defamatory, invasive of privacy, infringing of intellectual property rights, or otherwise injurious to third parties or objectionable and does not consist of or contain software viruses, political campaigning, commercial solicitation, chain letters, mass mailings, or any form of "spam." You may not use a false e-mail address, impersonate any person or entity, or otherwise mislead as to the origin of a card or other content. quizzes.ltd reserves the right (but not the obligation) to remove or edit such content, but does not regularly review posted content. If you do post content or submit material, and unless we indicate otherwise, you grant quizzes.ltd and its associates a nonexclusive, royalty-free, perpetual, irrevocable, and fully sublicensable right to use, reproduce, modify, adapt, publish, translate, create derivative works from, distribute, and display such content throughout the world in any media. You grant quizzes.ltd and its associates and sublicensees the right to use the name that you submit in connection with such content, if they choose. You represent and warrant that you own or otherwise control all of the rights to the content that you post: that the content is accurate: that use of the content you supply does not violate this policy and will not cause injury to any person or entity: and that you will indemnify quizzes.ltd or its associates for all claims resulting from content you supply. quizzes.ltd has the right but not the obligation to monitor and edit or remove any activity or content. quizzes.ltd takes no responsibility and assumes no liability for any content posted by you or any third party.
        <br><br>
        RISK OF LOSS
        <br><br>
        All items purchased from quizzes.ltd are made pursuant to a shipment contract. This basically means that the risk of loss and title for such items pass to you upon our delivery to the carrier.
        Certain products or services may be available exclusively online through the website. These products or services may have limited quantities and are subject to return or exchange only according to our Return Policy.
        <br><br>
        PRODUCT DESCRIPTIONS
        <br><br>
        quizzes.ltd and its associates attempt to be as accurate as possible. However, quizzes.ltd does not warrant that product descriptions or other content of this site is accurate, complete, reliable, current, or error-free. If a product offered by quizzes.ltd itself is not as described, your sole remedy is to return it in unused condition.
        <br><br>
        DISCLAIMER OF WARRANTIES AND LIMITATION OF LIABILITY THIS SITE IS PROVIDED BY quizzes.ltd ON AN "AS IS" AND "AS AVAILABLE" BASIS. quizzes.ltd MAKES NO REPRESENTATIONS OR WARRANTIES OF ANY KIND, EXPRESS OR IMPLIED, AS TO THE OPERATION OF THIS SITE OR THE INFORMATION, CONTENT, MATERIALS, OR PRODUCTS INCLUDED ON THIS SITE. YOU EXPRESSLY AGREE THAT YOUR USE OF THIS SITE IS AT YOUR SOLE RISK. TO THE FULL EXTENT PERMISSIBLE BY APPLICABLE LAW, quizzes.ltd DISCLAIMS ALL WARRANTIES, EXPRESS OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE. quizzes.ltd DOES NOT WARRANT THAT THIS SITE, ITS SERVERS, OR E-MAIL SENT FROM quizzes.ltd ARE FREE OF VIRUSES OR OTHER HARMFUL COMPONENTS. quizzes.ltd WILL NOT BE LIABLE FOR ANY DAMAGES OF ANY KIND ARISING FROM THE USE OF THIS SITE, INCLUDING, BUT NOT LIMITED TO DIRECT, INDIRECT, INCIDENTAL, PUNITIVE, AND CONSEQUENTIAL DAMAGES. CERTAIN STATE LAWS DO NOT ALLOW LIMITATIONS ON IMPLIED WARRANTIES OR THE EXCLUSION OR LIMITATION OF CERTAIN DAMAGES. IF THESE LAWS APPLY TO YOU, SOME OR ALL OF THE ABOVE DISCLAIMERS, EXCLUSIONS, OR LIMITATIONS MAY NOT APPLY TO YOU, AND YOU MIGHT HAVE ADDITIONAL RIGHTS.
        <br><br>
        APPLICABLE LAW
        <br><br>
        By visiting quizzes.ltd, you agree that the laws of the state of DEFINE STATE, DEFINE COUNTRY, without regard to principles of conflict of laws, will govern these Conditions of Use and any dispute of any sort that might arise between you and quizzes.ltd or its associates.
        <br><br>
        DISPUTES
        <br><br>
        Any dispute relating in any way to your visit to quizzes.ltd or to products you purchase through quizzes.ltd hall be submitted to confidential arbitration in DEFINE_STATE, DEFINE_COUNTRY, except that, to the extent you have in any manner violated or threatened to violate quizzes.ltd s intellectual property rights, quizzes.ltd may seek injunctive or other appropriate relief in any state or federal court in the state of DEFINE_STATE, DEFINE_COUNTRY, and you consent to exclusive jurisdiction and venue in such courts. Arbitration under this agreement shall be conducted under the rules then prevailing of the The International Centre for Dispute Resolution Canada (ICDR® Canada) . The arbitrators award shall be binding and may be entered as a judgment in any court of competent jurisdiction. To the fullest extent permitted by applicable law, no arbitration under this Agreement shall be joined to an arbitration involving any other party subject to this Agreement, whether through class arbitration proceedings or otherwise.
        <br><br>
        SITE POLICIES, MODIFICATION, AND SEVERABILITY
        <br><br>
        Please review our other policies, such as our Shipping and Returns policy, posted on this site. These policies also govern your visit to MY quizzes.ltd. We reserve the right to make changes to our site, policies, and these Conditions of Use at any time. If any of these conditions shall be deemed invalid, void, or for any reason unenforceable, that condition shall be deemed severable and shall not affect the validity and enforceability of any remaining condition.
        <br><br>
        QUESTIONS:
        <br><br>
        Questions regarding our Conditions of Usage, Privacy Policy, or other policy related material can be directed to our support staff by clicking on the "Contact Us" link in the side menu..
        <br><br>
        Important Information: All Sales Are Final, No Returns
        <br><br>
        At Quizzes.ltd, we strive to provide you with the best online quiz experience.
        <br><br>
        We have invested significant time and effort into creating high-quality quizzes to meet your educational and entertainment needs. To maintain the integrity and fairness of our services, we would like to inform you that all sales made through our platform are final, and we do not accept returns or provide refunds.
        <br><br>
        We understand that you may have questions or concerns about this policy, so we want to explain the reasons behind it. By offering digital quizzes, we aim to ensure that every customer receives the same experience and equal access to our content. Once a quiz has been purchased and accessed, it cannot be returned or exchanged since the information has already been made available to you.
        <br><br>
        To assist you in making an informed decision before purchasing a quiz, we provide detailed descriptions, and other relevant information. We recommend reviewing this information thoroughly to ensure the quiz aligns with your requirements and expectations. If you have any questions or need assistance, our dedicated support team is always ready to help. Reach out to us via "Contact Us" form and we will be happy to assist you.
        <br><br>
        We appreciate your understanding and cooperation regarding our "All Sales Are Final, No Returns" policy. We are committed to continuously improving our quizzes and delivering exceptional service to our valued customers. Your satisfaction is our top priority, and we will always strive to exceed your expectations.
        <br><br>
        Thank you for choosing Quizzes.ltd for your online quiz needs. We look forward to providing you with an enjoyable and rewarding learning experience!
        <br><br>        
        </div>

    </div>

        
    @endsection
