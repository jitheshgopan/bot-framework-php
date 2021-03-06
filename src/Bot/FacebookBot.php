<?php namespace hatxor\BotFramework;

use hatxor\BotFramework\Http;
use hatxor\BotFramework\Auth;
use hatxor\BotFramework\BotException;
use hatxor\BotFramework\Bot;

final class FacebookBot extends Bot {

    protected $serviceUrl = "https://facebook.botframework.com";

    protected $type_text_message = "message";

    /**
     * [__construct description]
     * @param [type] $client [description]
     * @param [type] $secret [description]
     */
    public function __construct( $client, $secret, $config ) {

        parent::__construct( $client, $secret, $config );

    }
    

    /**
     * [addMessage description]
     * @param  string $to      [description]
     * @param  string $message [description]
     * @return array           [description]
     */
    public function addMessage( $to, $message, $extra = array() ) {
        
        // Execute
        $url = $this->serviceUrl . '/' . $this->serviceVersion . '/conversations/' . $to . '/activities';

        $params = array(

            'type' => $this->type_text_message,
            "text" => $message,
            "from" => array( "id" => $this->config['fb_bot_id'], "name" => $this->config['fb_bot_name'] ),

        );

        $httpResponse = $this->do_request( $url, 'POST_RAW', $params, array( 'Content-Type: application/json' ) );

        return $httpResponse;
        
    }
    

    /**
     * [addAttachment description]
     * @param  string $to      [description]
     * @param  string $message [description]
     * @return array           [description]
     */
    public function addAttachment( $to, $type, $content, $extra = array(), $options = array() ) {

        if( !isset( $this->type_attachment[ $type ] ) )

            throw new BotException( "Wuops! The media type doesn't exist.", 1 );
        
        // Execute
        $url = $this->serviceUrl . '/' . $this->serviceVersion . '/conversations/' . $to . '/activities';

        $methodName = "_create" . $type;

        $params = array(

            'type' => $this->type_attachment[ $type ],
            "attachments" => $this->$methodName( $content, $extra, $options ),
            "from" => array( "id" => $this->config['fb_bot_id'], "name" => $this->config['fb_bot_name'] ),

        );

        if( isset( $options['summary'] ) )

            $params['summary'] = $options['summary'];

        $httpResponse = $this->do_request( $url, 'POST_RAW', $params, array( 'Content-Type: application/json' ) );

        return $httpResponse;
        
    }

}
