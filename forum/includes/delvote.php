<?php

defined('_IN_JOHNCMS') or die('Error: restricted access');

if ($rights == 3 || $rights >= 6) {
    /** @var PDO $db */
    $db = App::getContainer()->get(PDO::class);

    $topic_vote = $db->query("SELECT COUNT(*) FROM `cms_forum_vote` WHERE `type`='1' AND `topic` = '$id'")->fetchColumn();
    require('../incfiles/head.php');

    if ($topic_vote == 0) {
        echo functions::display_error($lng['error_wrong_data']);
        require('../incfiles/end.php');
        exit;
    }

    if (isset($_GET['yes'])) {
        $db->exec("DELETE FROM `cms_forum_vote` WHERE `topic` = '$id'");
        $db->exec("DELETE FROM `cms_forum_vote_users` WHERE `topic` = '$id'");
        $db->exec("UPDATE `forum` SET  `realid` = '0'  WHERE `id` = '$id'");
        echo $lng_forum['voting_deleted'] . '<br /><a href="' . $_SESSION['prd'] . '">' . $lng['continue'] . '</a>';
    } else {
        echo '<p>' . $lng_forum['voting_delete_warning'] . '</p>';
        echo '<p><a href="?act=delvote&amp;id=' . $id . '&amp;yes">' . $lng['delete'] . '</a><br />';
        echo '<a href="' . htmlspecialchars(getenv("HTTP_REFERER")) . '">' . $lng['cancel'] . '</a></p>';
        $_SESSION['prd'] = htmlspecialchars(getenv("HTTP_REFERER"));
    }
} else {
    header('location: ../index.php?err');
}
