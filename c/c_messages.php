<?php

class c_messages {

    public function obtenerMessages() {
        if(_bloquear_ && $_SESSION['type'] == 0 ){
            echo 'Access Denied';
            exit;
        }
        
        $messages = new messages();
        $pdo = $messages->obtenerMessages();
        $return = $pdo->fetchAll(PDO::FETCH_ASSOC);
        $array = array_reverse($return);
        
        array_walk($array, function (&$elemento, $clave){

            $elemento['time'] = date('h:i a d/m/Y', $elemento['time']);

            $ext = array('.jpg', '.png', '.gif');
            foreach ($ext as $c){
                if ( strpos($elemento['text'], $c) !== false ) {
                    $elemento['text'] = '<img src="' . $elemento['text'] . '" width="100%">';
                }
            }

            if ( substr($elemento['text'], 0, 3) === "em_" ) {
                $elemento['text'] = '<i style="font-size: 85px;" class="em ' . $elemento['text'] . '"></i>';
            }
            
            if ( substr($elemento['text'], 0, 3) === "ba_" ) {
                $elemento['text'] = '<i style="font-size: 300px;width: -webkit-fill-available;" class="em ' . $elemento['text'] . '"></i>';
            }
            
            if ( substr($elemento['text'], 0, 1) === "<" && substr($elemento['text'], 0, 9) !== "<i style=" ) {
                $elemento['text'] = '<code>' .  htmlspecialchars($elemento['text']) . '</code>';
            }
            
            if( strpos( $elemento['text'], "watch?v=" ) !== false ){
                $video_id = explode("watch?v=", $elemento['text']);

                $elemento['text'] = '<img src="./assets/img/youtube.png" width="25%" style="position:absolute;right: 0;margin: 15px;bottom: 0;">
                <img onClick="showYoutubeVideo(\'' . $video_id[1] . '\')" src="http://img.youtube.com/vi/' . $video_id[1] . '/maxresdefault.jpg" width="100%">';
            }

            if( strpos( $elemento['text'], "youtu.be/" ) !== false){
                $video_id = explode("youtu.be/", $elemento['text']);

                $elemento['text'] = '<img src="./assets/img/youtube.png" width="25%" style="position:absolute;right: 0;margin: 15px;bottom: 0;">
                <img onClick="showYoutubeVideo(\'' . $video_id[1] . '\')" src="http://img.youtube.com/vi/' . $video_id[1] . '/maxresdefault.jpg" width="100%">';
            }
            
            if( substr( $elemento['text'], 0, 1) === "*" ){
                if( substr( $elemento['text'], -1, 1) === "*" ){
                    $text = explode("*", $elemento['text']);
                    $elemento['text'] = '<b>' . $text[1] . '</b>';
                }
            }

            if( substr( $elemento['text'], 0, 1) === "_" ){
                if( substr( $elemento['text'], -1, 1) === "_" ){
                    $text = explode("_", $elemento['text']);
                    $elemento['text'] = '<u>' . $text[1] . '</u>';
                }
            }

            if( substr( $elemento['text'], 0, 1) === "-" ){
                if( substr( $elemento['text'], -1, 1) === "-" ){
                    $text = explode("-", $elemento['text']);
                    $elemento['text'] = '<i>' . $text[1] . '</i>';
                }
            }

            if( substr( $elemento['text'], 0, 1) === "~" ){
                if( substr( $elemento['text'], -1, 1) === "~" ){
                    $text = explode("~", $elemento['text']);
                    $elemento['text'] = '<del>' . $text[1] . '</del>';
                }
            }

            if( substr( $elemento['text'], 0, 3) === "/r/" ){
                    $text = explode("/r/", $elemento['text']);
                    $elemento['text'] = '<p style="color:red;">' . $text[1] . '</p>';
            }

            if( substr( $elemento['text'], 0, 3) === "/b/" ){
                    $text = explode("/b/", $elemento['text']);
                    $elemento['text'] = '<p style="color:blue;">' . $text[1] . '</p>';
            }

            if( substr( $elemento['text'], 0, 3) === "/v/" ){
                    $text = explode("/v/", $elemento['text']);
                    $elemento['text'] = '<p style="color:darkviolet;">' . $text[1] . '</p>';
            }

            if( substr( $elemento['text'], 0, 3) === "/f/" ){
                    $text = explode("/f/", $elemento['text']);
                    $elemento['text'] = '<p style="color:fuchsia;">' . $text[1] . '</p>';
            }

            if( substr( $elemento['text'], 0, 3) === "/g/" ){
                    $text = explode("/g/", $elemento['text']);
                    $elemento['text'] = '<p style="color:green;">' . $text[1] . '</p>';
            }

            if( substr( $elemento['text'], 0, 4 ) === "http" ){
                $elemento['text'] = '<a target="_BLANK" href="' . $elemento['text'] . '">' . $elemento['text'] . '</a>';
            }



            if( substr( $elemento['text'], 0, 2 ) === "//" ){
                $elemento['text'] = '<a target="_BLANK" href="' . $elemento['text'] . '"><img src="' . $elemento['text'] . '/favicon.ico" width="25px" style="display: inline-block;"><span style="margin-left:5px">' . $elemento['text'] . '</span></a>';
            }
            
        });

        $return = null;

        foreach ($array as $c => $v) {
            $return .= '<div class="row msg-container"><div class="col-md-12 col-xs-12"><div class="chat-msg"><div class="chat-msg-author">';
            $return .= '<strong>' . $v['name'] . ' ' . $v['surname'] . '</strong>';
            $return .= '<span class="date">' . $v['time'] . '</span>';
            $return .= '</div>';
            $return .= '<p>' . $v['text'] . '</p>';
            $return .= '</div></div></div>';
        }

        return $return;

    
    }
    
    public function sendMessage($post_text, $post_id) {
        
        if (!empty($post_text)){
            $messages = new messages();
            $text = html_entity_decode($post_text);
            $id = $this->sanitizeString($post_id);
            $time = date('Y-m-d H:i:s');
            return $messages->sendMessage($text, $id, $time);
        }
        
    }
    
    private function sanitizeString($string){
        return filter_var($string, FILTER_SANITIZE_STRING);
    }
}
