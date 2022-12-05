<!-- ETO PANG EDIT NG FEEDBACK MODAL-->
    <div class="modal fade" role="dialog" tabindex="-1" id="editratestoremodal<?php echo $fidback['ratingID']?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <form action="assets/includes/edit-feedback-inc.php?shopID=<?php echo $val['userID']?>" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <div class="head-title">
                        <h5>Edit your rating</h5>
                    </div><button type="reset" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="star-widget-container">
                        <div class="star-widget">
                        <?php
                            if($fidback['rating'] == "5"){
                        ?>
                            <input type="radio" id="rate-5" value="5" name="rate" checked><label class="form-label fas fa-star" for="rate-5"></label>
                            <input type="radio" id="rate-4" value="4" name="rate"><label class="form-label fas fa-star" for="rate-4"></label>
                            <input type="radio" id="rate-3" value="3" name="rate"><label class="form-label fas fa-star" for="rate-3"></label>
                            <input type="radio" id="rate-2" value="2" name="rate"><label class="form-label fas fa-star" for="rate-2"></label>
                            <input type="radio" id="rate-1" value="1" name="rate"><label class="form-label fas fa-star" for="rate-1"></label>
                        <?php
                            }else if($fidback['rating'] == "4"){
                        ?>    
                            <input type="radio" id="rate-5" value="5" name="rate"><label class="form-label fas fa-star" for="rate-5"></label>
                            <input type="radio" id="rate-4" value="4" name="rate" checked><label class="form-label fas fa-star" for="rate-4"></label>
                            <input type="radio" id="rate-3" value="3" name="rate"><label class="form-label fas fa-star" for="rate-3"></label>
                            <input type="radio" id="rate-2" value="2" name="rate"><label class="form-label fas fa-star" for="rate-2"></label>
                            <input type="radio" id="rate-1" value="1" name="rate"><label class="form-label fas fa-star" for="rate-1"></label>
                        <?php
                            }else if($fidback['rating'] == "3"){
                        ?>
                            <input type="radio" id="rate-5" value="5" name="rate"><label class="form-label fas fa-star" for="rate-5"></label>
                            <input type="radio" id="rate-4" value="4" name="rate"><label class="form-label fas fa-star" for="rate-4"></label>
                            <input type="radio" id="rate-3" value="3" name="rate" checked><label class="form-label fas fa-star" for="rate-3"></label>
                            <input type="radio" id="rate-2" value="2" name="rate"><label class="form-label fas fa-star" for="rate-2"></label>
                            <input type="radio" id="rate-1" value="1" name="rate"><label class="form-label fas fa-star" for="rate-1"></label>
                        <?php
                            }else if($fidback['rating'] == "2"){
                        ?>
                            <input type="radio" id="rate-5" value="5" name="rate"><label class="form-label fas fa-star" for="rate-5"></label>
                            <input type="radio" id="rate-4" value="4" name="rate"><label class="form-label fas fa-star" for="rate-4"></label>
                            <input type="radio" id="rate-3" value="3" name="rate"><label class="form-label fas fa-star" for="rate-3"></label>
                            <input type="radio" id="rate-2" value="2" name="rate" checked><label class="form-label fas fa-star" for="rate-2"></label>
                            <input type="radio" id="rate-1" value="1" name="rate"><label class="form-label fas fa-star" for="rate-1"></label>
                        <?php
                            }else if($fidback['rating'] == "1"){
                        ?>
                            <input type="radio" id="rate-5" value="5" name="rate"><label class="form-label fas fa-star" for="rate-5"></label>
                            <input type="radio" id="rate-4" value="4" name="rate"><label class="form-label fas fa-star" for="rate-4"></label>
                            <input type="radio" id="rate-3" value="3" name="rate"><label class="form-label fas fa-star" for="rate-3"></label>
                            <input type="radio" id="rate-2" value="2" name="rate"><label class="form-label fas fa-star" for="rate-2"></label>
                            <input type="radio" id="rate-1" value="1" name="rate" checked><label class="form-label fas fa-star" for="rate-1"></label>
                        <?php
                            }
                        ?>
                        </div>
                    </div>
                    <div class="text-area">
                        <textarea class="form-control" name="feedback" required><?php echo $fidback['feedback']?></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn" type="submit" name="submit2">Update</button>
                </div>
            </form>
            </div>
        </div>
    </div>
<!-- END -->