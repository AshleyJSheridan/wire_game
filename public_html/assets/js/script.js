/*	Author:
		TMW - George Bardis
*/

// ======================================
// === Declare global 'TMW' namespace ===
// ======================================
var TMW = window.TMW || {};

// Create a closure to maintain scope of the '$' and remain compatible with other frameworks
(function($) {

	$(function() {
		TMW.SiteSetup.init();

	});

	/* optional triggers

	// WINDOW.LOAD
	$(window).load(function() {

	});

	// WINDOW.RESIZE
	$(window).resize(function() {

	});

	*/

})(jQuery);

TMW.SiteSetup = {
    
	pollingURL              : '/competition/wire-game/getmotiondata',
        startGameURL            : '/competition/wire-game/gamestart',
        endGameURL              : '/competition/wire-game/gameend',
        defaultTwitterHandle    : 'tmwagency',
        playerRFHandleId        : null,
        gameStatusFlag          : 'off',
        gameProgress            : 0,
        gameTime                : 0,

	init : function () {
            TMW.SiteSetup.getGameStatusData();
	},
        
        getGameStatusData : function(){
            $.ajax({ url: TMW.SiteSetup.pollingURL, success: function(data){
                TMW.SiteSetup.checkGameStatusChange(data.game_status, data.RFHandleId);
            }, dataType: "json", complete: TMW.SiteSetup.getGameStatusData, timeout: 30000 });
           // }, dataType: "json", timeout: 30000 });
        },
        
        checkGameStatusChange : function(gameStatus, playerRFHandleId){
    
                if(gameStatus == TMW.SiteSetup.gameStatusFlag){
                    return;
                }
                else if(gameStatus != TMW.SiteSetup.gameStatusFlag){
                    TMW.SiteSetup.gameStatusFlag = gameStatus;
                    TMW.SiteSetup.playerRFHandleId = playerRFHandleId; 
                    console.log(gameStatus);
                    if(gameStatus == 'on'){
                        TMW.SiteSetup.startGame();
                    }
                    else{
                        TMW.SiteSetup.endGame();                        
                    }
                }            
        },
        
        startGame : function(){
            $.ajax({ 
                url:    TMW.SiteSetup.startGameURL, 
                data:   { playerRFHandleId: TMW.SiteSetup.playerRFHandleId },
                
                success: function(data){
                TMW.SiteSetup.setPlayerDetails(data.playerDetails);
            }, dataType: "json", complete: TMW.SiteSetup.getGameStatusData, timeout: 30000 });
        },
        
        endGame : function(){
            console.log('endGame');     
        },
                
        setPlayerDetails : function(playerDetails){
            if(!playerDetails.twitterhandle){
                playerDetails.twitterhandle = TMW.SiteSetup.defaultTwitterHandle;
                playerName = playerDetails.firstname;
            }
            else{
                playerName = playerDetails.twitterhandle;
            }
            var playerDataHTML = '<a target="_blank" title="' + playerName +'" href="https://twitter.com/' + playerDetails.twitterhandle + '">' + playerName + '<img alt="' + playerName + '" src="' + playerDetails.playerTwitterImg + '"></a>'; 
            $('.playerDetails').html(playerDataHTML).fadeIn('slow');
        },
                
        setScoreBoard : function(){
            console.log('setScoreBoard');    
        },
                
        setVideo : function(){
            console.log('setVideo');     
        }

};
