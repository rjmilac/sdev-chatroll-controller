<?php
    /**
     *  Event Chatroll Controller
     *
     *
     * @package SDEV
     * @subpackage SDEV WP
     * @since 1.0
     */

    namespace SDEV\Controller\Event;

    class Chatroll extends \SDEV\Controller implements \SDEV\Interfaces\WPXHRActionControllerInterface {

        protected $_block;

        public function __construct(){
            parent::__construct();
            $this->_login = new \SDEV\Block\Event\Login();
        }

        public function registerActions(){
            add_action( 'wp_ajax_event_chatroll_generate', array($this, 'generate') );
            add_action( 'wp_ajax_nopriv_event_chatroll_generate', array($this, 'generate') );
        }

        public function generate(){
            $response = [
                'success' => true,
                'code' => 200,
                'message' => 'SUCCESS',
                'iframe' =>''
            ];

            $signature = get_field('sso_signature_key', $_POST['evt_id']);
            $slug = get_field('chatroll_slug', $_POST['evt_id']);
            $cid = get_field('chatroll_id', $_POST['evt_id']);

            $uid = $this->_login->getUserID();        // Current user id
            $uname = $this->_login->getUserName();      // Current user name
            $ulink = '';                                // Current user profile URL (leave blank for none)
            $upic = '';                                 // Current user profile picture URL (leave blank for none)
            $ismod = 0;                                 // Is current user a moderator?
            $sig = md5($uid . $uname . $ismod . $signature);
            $ssoParams = '&uid=' . $uid . "&uname=" . urlencode($uname) . "&ulink=" . urlencode($ulink) . "&upic=" . urlencode($upic) . "&ismod=" . $ismod . "&sig=" . $sig;

            $response['iframe'] = "<iframe width='100%' height='350px' src='https://chatroll.com/embed/chat/".$slug."?id=".$cid."&platform=php".$ssoParams."' frameborder='0' scrolling='no' marginheight='0' marginwidth='0' allowtransparency='true'></iframe>";

            echo json_encode($response);
            exit;
        }

    }

?>