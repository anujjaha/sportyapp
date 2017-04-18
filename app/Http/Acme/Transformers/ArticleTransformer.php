<?php

namespace App\Http\Acme\Transformers;

use App\Http\Acme\Transformers;

class ArticleTransformer extends Transformer {

    /**
     * Created By: Sagar Dave
     * Created At: 12/07/2016
     * @param type $data
     * @return type
     */
    public function transform($data) {
        return[
            'FeedId' => $data['id'],
            'User' => array(
                'UserId' => $data['article_user']['id'],
                'QuickBlocksId' => $data['article_user']['quick_blocks_id'],
                'MobileNumber' => $data['article_user']['mobile_number'],
                'Name' => $data['article_user']['name'],
                'ProfilePhoto' => $data['article_user']['profile_photo'] ? $this->getUserImage($data['article_user']['profile_photo']) : "",
                'Specialty' => $data['article_user']['specialization'],
            ),
            'FeedDescription' => $data['content'],
            'FeedImage' => !empty($data['article_image']) ? $this->getArticleImage($data['article_image'][0]['image'], $data['id']) : "",
            'CommentCount' => $data['comment_count'],
            'LikeCount' => $data['like_count'],
            'IsLiked' => $data['is_liked'],
            'FeedTime' => strtotime($data['created_at']),
        ];
    }

    /*
     * Get Article Image Path
     * Created By: Sagar Dave
     * Created At: 12/07/2016
     * @param string $profilePhoto
     * @return string
     */

    public function getArticleImage($profilePhoto, $ArticleId) {
        return $profilePhoto ? url('/') . '/images/' . config('backend.article_image_folder') . '/' . $ArticleId . '/' . $profilePhoto : "";
    }

    /*
     * Get User Image Path
     * Created By: Sagar Dave
     * Created At: 12/07/2016
     * @param string $profilePhoto
     * @return string
     */

    public function getUserImage($profilePhoto) {
        return $profilePhoto ? url('/') . '/images/' . config('access.users.image_folder') . '/' . $profilePhoto : "";
    }

    /*
     * Get Article collection Array
     * Created By: Sagar Dave
     * Created At: 12/07/2016
     * 
     * @param array $data
     * @return array
     */

    public function transformArticleCollection(array $items) {
        return array_map([$this, 'singeArticleTransform'], $items);
    }

    /*
     * Get Single article Data
     * Created By: Sagar Dave
     * Created At: 12/07/2016
     * 
     * @param array $data
     * @return array
     */

    public function singeArticleTransform($data) {

        return[
            'FeedId' => $data['id'],
            'User' => array(
                'UserId' => $data['article_comment_user']['id'],
                'Name' => $data['article_comment_user']['name'],
                'ProfilePhoto' => $data['article_comment_user']['profile_photo'] ? $this->getUserImage($data['article_comment_user']['profile_photo']) : ""
            ),
            'Comment' => $data['comment'],
            'CommentTime' => strtotime($data['created_at']),
//            'CommentImages' => $this->getCommentImages($data['forum_comment_image'],$data['id'])
        ];
    }

//    public function getArticleImages($images) {
//        $articleArray = array();
//        foreach ($images as $iK => $iV) {
//            $articleArray[$iK] = url('/') . '/images/article/' . $iV['image'];
//        }
//        return $articleArray;
//    }

    /*
     * Get Comment Array
     * Created By: Sagar Dave
     * Created At: 12/07/2016
     * 
     * @param array $data
     * @return array
     */

    public function transformComments($data) {
        return[
            'CommentCount' => $data['comment_count']
        ];
    }

    /*
     * Get Comment Array
     * Created By: Sagar Dave
     * Created At: 12/07/2016
     * 
     * @param array $data
     * @return array
     */

    public function transformLikes($data) {
        return[
            'LikeCount' => $data['like_count']
        ];
    }

}
