<?php require "../base.php"; ?>

<?php include '_head.php'; ?>


<div class="container">

    <!-- Welcome Banner -->
    <div style="position: relative; background: url('picture/background_1.jpg') center/cover no-repeat; padding: 100px 20px; border-radius: 20px; margin-bottom: 50px; overflow: hidden; text-align: center; color: #831f1f; box-shadow: 0 6px 20px rgba(0,0,0,0.1);">
        <div style="position: absolute; top:0; left:0; width:100%; height:100%; background: rgba(255,255,255,0.7); z-index:1;"></div>

        <div style="position: relative; z-index:2; display: flex; align-items: center; justify-content: center; flex-direction: column; gap: 20px;">
            
            <div style="display: flex; align-items: center; gap: 20px;">
                <!-- Logo -->
                <div style="background: rgba(255,255,255,0.6); padding: 10px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <img src="picture/logo1.png" alt="Tarumt cafeteria  Logo" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                </div>
                <!-- Welcome Text -->
                <div>
                    <h1 style="font-size: 40px; margin: 0;">TARUMT Cafeteria Food Ordering System!</h1>
                </div>
            </div>

            <p style="font-size: 20px;">Fast ordering, easy delivery, and every meal worth looking forward to!</p>

        </div>
    </div>


    <!-- About Us -->
    <section style="text-align: center; margin-bottom: 50px;">
        <div style="display: flex; align-items: center; justify-content: center; gap: 15px; margin-bottom: 20px;">
            <h1>About US</h1>
        </div>
        <p class="subtext">Deliciousness at Your Fingertips! 🍔🍩</p>
        <p style="font-size: 18px; line-height: 1.8; padding: 0 30px;">
            With us, we believe that enjoying cafeteria food should be quick, simple, and stress-free.
We are committed to providing TAR UMT students and staff with a convenient food ordering experience while serving very delicious, freshly prepared meals every day.
        </p>
    </section>


 

    <!-- Why Choose JQC -->
    <section style="text-align: center; margin-bottom: 50px;">
        <h1>Why Choose TARUMT Cafeteria?</h1>
        <ul style="list-style: none; padding: 0; font-size: 18px; line-height: 1.8; margin-top: 30px;">
            <li>⚡ <b>Fast Ordering:</b> Complete orders in just a few steps, save your time!</li>
            <li>🍴 <b>Quality Food:</b> Carefully selected local favorites, truly delicious.</li>
            <li>💬 <b>Smooth Service:</b> Best user experience with caring support!</li>
        </ul>
    </section>

    <!-- Feedback Center -->
    <section style="margin-bottom: 50px;">
        <h1 style="text-align: center;">Feedback Center</h1>
        <p class="subtext" style="text-align: center;">Choose to view or submit feedback.</p>

        <div class="button-group">
            <a href="feedback_view.php" class="action-btn">
            <div class="btn-icon">📄</div>
            <div class="btn-text">
                <h3>View Feedback</h3>
                <p>See what others have said.</p>
            </div>
            </a>

            <a href="feedback_add.php" class="action-btn">
            <div class="btn-icon">📝</div>
            <div class="btn-text">
                <h3>Submit Feedback</h3>
                <p>Share your experience with us.</p>
            </div>
            </a>
        </div>
    </section>

</div>

</body>
</html>
