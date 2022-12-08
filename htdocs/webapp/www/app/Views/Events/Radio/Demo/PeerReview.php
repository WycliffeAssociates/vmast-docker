<?php
require(app_path() . "Views/Components/CommentEditor.php");
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="main_content_title">
            <div class="demo_title"><?php echo __("demo"). " (".__("rad").")" ?></div>
            <div><?php echo __("step_num", ["step_number" => 4]) . ": " . __("peer-review") ?></div>
        </div>
    </div>

    <div class="">
        <div class="main_content">
            <form action="" method="post" id="main_form">
                <div class="main_content_text row" style="padding-left: 15px">
                    <h4>Español Latin America - RADIO - Radio 2 - Broadcast Introduction</h4>

                    <div class="col-sm-12 no_padding">
                        <div class="flex_container chunk_block">
                            <div class="chunk_verses flex_left" dir="ltr">
                                <div class="verse_text" data-verse="1">
                                    <p class="verse_text_1" style="height: 80px;">Broadcast 1</p>
                                </div>
                            </div>
                            <div class="flex_middle editor_area" dir="ltr">
                                <div class="vnote verse_block" data-verse="1">
                                    <textarea name="chunks[0]" class="peer_verse_ta textarea verse_text_1"
                                              style="overflow-x: hidden; overflow-wrap: break-word; height: 80px;">Transmisión 1</textarea>
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php
                                $comments = $data["comments"][0];
                                $hasComments = !empty($comments);
                                $commentsNumber = sizeof($comments);
                                $myMemberID = 0;
                                $enableFootNotes = false;
                                require(app_path() . "Views/Components/Comments.php");
                                ?>
                            </div>
                        </div>
                        <div class="chunk_divider"></div>
                        <div class="flex_container chunk_block">
                            <div class="chunk_verses flex_left" dir="ltr">
                                <div class="verse_text" data-verse="1">
                                    <p class="verse_text_1" style="height: 80px;">Broadcast Introduction</p>
                                </div>
                            </div>
                            <div class="flex_middle editor_area" dir="ltr">
                                <div class="vnote verse_block" data-verse="1">
                                    <textarea name="chunks[0]" class="peer_verse_ta textarea verse_text_1"
                                              style="overflow-x: hidden; overflow-wrap: break-word; height: 80px;">Introducción a la transmisión</textarea>
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php
                                $comments = [$data["comments"][0][1]];
                                $hasComments = !empty($comments);
                                $commentsNumber = sizeof($comments);
                                $myMemberID = 0;
                                $enableFootNotes = false;
                                require(app_path() . "Views/Components/Comments.php");
                                ?>
                            </div>
                        </div>
                        <div class="chunk_divider"></div>
                        <div class="flex_container chunk_block">
                            <div class="chunk_verses flex_left" dir="ltr">
                                <div class="verse_text" data-verse="2">
                                    <p class="verse_text_1" style="height: 49px;"><strong>LIAM</strong></p>
                                    <p class="verse_text_2" style="height: 340px;">Hello and welcome to our broadcast
                                        covering an exciting new event that just took place in our area. Teams of
                                        villagers gathered with some trainers from Wycliffe Associates to start a New
                                        Testament translation into their own mother-tongue language! These villagers are
                                        not educated theologians. They are Christians who love the Lord and love their
                                        people. The leaders of Wycliffe Associates have developed a method for
                                        translation called MAST—Mobilized Assistance Supporting Translation. This method
                                        has 8 distinct steps, four for drafting and four for checking the translation.
                                        The only requirements for translators to use this method is that they know two
                                        languages—a language of wider communication, such as your national language, and
                                        their own mother tongue. It is amazing that no other education is necessary. In
                                        fact, you may be feeling a little skeptical right now that this is even
                                        possible. Let me assure you that it is! In just the past six years, over 1500
                                        languages in the world have begun work on a New Testament translation using this
                                        method. More than 300 of those languages have completed their New Testaments and
                                        these Scriptures are being used in their communities! Not only that, but some
                                        have gone on to translate the Old Testament as well. And some have moved to
                                        other regions to teach other language communities this simple methodology for
                                        Bible translation. </p>
                                </div>
                            </div>
                            <div class="flex_middle editor_area" dir="ltr">
                                <div class="vnote verse_block" data-verse="2">
                                    <textarea name="chunks[1][name]" class="peer_verse_ta textarea verse_text_1"
                                              rows="1"
                                              style="overflow-x: hidden; overflow-wrap: break-word; height: 49px;">LIAM</textarea>
                                    <textarea name="chunks[1][text]" class="peer_verse_ta textarea verse_text_2"
                                              style="overflow-x: hidden; overflow-wrap: break-word; min-height: 340px;">Hola y bienvenidos a nuestra transmisión que cubre un nuevo y emocionante evento que acaba de tener lugar en nuestra área. ¡Equipos de aldeanos se reunieron con algunos entrenadores de Wycliffe Associates para comenzar una traducción del Nuevo Testamento a su propio idioma materno! Estos aldeanos no son teólogos educados. Son cristianos que aman al Señor y a su pueblo. Los líderes de Wycliffe Associates han desarrollado un método de traducción llamado MAST: Mobilized Assistance Supporting Translation. Este método tiene 8 pasos distintos, cuatro para redactar y cuatro para verificar la traducción. Los únicos requisitos para que los traductores utilicen este método es que conocen dos idiomas: un idioma de comunicación más amplia, como su idioma nacional, y su propia lengua materna. Es sorprendente que no sea necesaria otra educación. De hecho, puede sentirse un poco escéptico en este momento de que esto sea posible. ¡Déjame asegurarte que lo es! En los últimos seis años, más de 1500 idiomas en el mundo han comenzado a trabajar en una traducción del Nuevo Testamento utilizando este método. ¡Más de 300 de esos idiomas han completado su Nuevo Testamento y estas Escrituras se están utilizando en sus comunidades! No solo eso, sino que algunos también han traducido el Antiguo Testamento. Y algunos se han mudado a otras regiones para enseñar a otras comunidades lingüísticas esta metodología simple para la traducción de la Biblia.</textarea>
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php
                                $hasComments = false;
                                $enableFootNotes = false;
                                require(app_path() . "Views/Components/Comments.php");
                                ?>
                            </div>
                        </div>
                        <div class="chunk_divider"></div>
                        <div class="flex_container chunk_block">
                            <div class="chunk_verses flex_left" dir="ltr">
                                <div class="verse_text" data-verse="3">
                                    <p class="verse_text_1" style="height: 49px;"><strong>SOFIA</strong></p>
                                    <p class="verse_text_2" style="height: 120px;">The purpose of these broadcasts is to
                                        share with you, our listeners, the amazing movement of God among minority
                                        languages. If you are involved in your community’s Bible translation now, we
                                        want this broadcast to encourage you in your important work. If you are not
                                        involved now, we pray that this broadcast will encourage you to become involved.
                                        Perhaps you are from one of those languages still waiting for Scripture. If so,
                                        please believe me, that what we share here can also work for you. </p>
                                </div>
                            </div>
                            <div class="flex_middle editor_area" dir="ltr">
                                <div class="vnote verse_block" data-verse="3">
                                    <textarea name="chunks[2][name]" class="peer_verse_ta textarea verse_text_1"
                                              rows="1"
                                              style="overflow-x: hidden; overflow-wrap: break-word; height: 49px;">SOFIA</textarea>
                                    <textarea name="chunks[2][text]" class="peer_verse_ta textarea verse_text_2"
                                              style="overflow-x: hidden; overflow-wrap: break-word; min-height: 120px;">El propósito de estas transmisiones es compartir con ustedes, nuestros oyentes, el sorprendente movimiento de Dios entre los idiomas minoritarios. Si ahora participa en la traducción de la Biblia de su comunidad, queremos que esta transmisión lo aliente en su importante trabajo. Si no está involucrado ahora, rogamos que esta transmisión lo anime a participar. Quizás eres de uno de esos idiomas que todavía está esperando las Escrituras. Si es así, créame, que lo que compartimos aquí también puede funcionar para usted.</textarea>
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false;
                                require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                        <div class="chunk_divider"></div>
                        <div class="flex_container chunk_block">
                            <div class="chunk_verses flex_left" dir="ltr">
                                <div class="verse_text" data-verse="4">
                                    <p class="verse_text_1" style="height: 49px;"><strong>LIAM</strong></p>
                                    <p class="verse_text_2" style="height: 280px;">Let me provide some data on a New
                                        Testament translation project. The New Testament has 7957 verses. If two
                                        translators commit to learning the process and translating 11 verses per day for
                                        a year, they will complete the New Testament in that one year! Now think about
                                        gathering a larger group. If ten people can commit to learning the process and
                                        working on 10 verses a day for three months, they will complete a New Testament!
                                        Translating God’s Word takes a team. And MAST leverages the idea of teamwork.
                                        The more people who commit to collaborate on the project, the faster it can go.
                                        The training for a MAST project is simple and can be intense. One method of
                                        training is to gather together for one or two weeks to work full time on
                                        learning the steps of MAST and implementing them in translation. You can also
                                        learn the steps by gathering a few Christian friends and trying the process as
                                        you listen to the broadcasts that follow this one. They will teach the method
                                        thoroughly, but if you need help or have more questions, we will give you
                                        contact information at the end of this broadcast, so you can get the help you
                                        need. </p>
                                </div>
                            </div>
                            <div class="flex_middle editor_area" dir="ltr">
                                <div class="vnote verse_block" data-verse="4">
                                    <textarea name="chunks[3][name]" class="peer_verse_ta textarea verse_text_1"
                                              rows="1"
                                              style="overflow-x: hidden; overflow-wrap: break-word; height: 49px;">LIAM</textarea>
                                    <textarea name="chunks[3][text]" class="peer_verse_ta textarea verse_text_2"
                                              style="overflow-x: hidden; overflow-wrap: break-word; min-height: 280px;">Permítanme proporcionar algunos datos sobre un proyecto de traducción del Nuevo Testamento. El Nuevo Testamento tiene 7957 versos. Si dos traductores se comprometen a aprender el proceso y a traducir 11 versículos por día durante un año, ¡completarán el Nuevo Testamento en ese año! Ahora piense en reunir un grupo más grande. Si diez personas pueden comprometerse a aprender el proceso y trabajar en 10 versículos por día durante tres meses, ¡completarán un Nuevo Testamento! Traducir la Palabra de Dios requiere un equipo. Y MAST aprovecha la idea del trabajo en equipo. Cuantas más personas se comprometan a colaborar en el proyecto, más rápido puede llegar. La capacitación para un proyecto MAST es simple y puede ser intensa. Un método de capacitación es reunirse durante una o dos semanas para trabajar a tiempo completo en aprender los pasos de MAST e implementarlos en la traducción. También puede aprender los pasos reuniendo algunos amigos cristianos y probando el proceso mientras escucha las transmisiones que siguen a esta. Le enseñarán el método a fondo, pero si necesita ayuda o tiene más preguntas, le daremos información de contacto al final de esta transmisión, para que pueda obtener la ayuda que necesita.</textarea>
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false;
                                require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                        <div class="chunk_divider"></div>
                        <div class="flex_container chunk_block">
                            <div class="chunk_verses flex_left" dir="ltr">
                                <div class="verse_text" data-verse="5">
                                    <p class="verse_text_1" style="height: 49px;"><strong>SOFIA</strong></p>
                                    <p class="verse_text_2" style="height: 340px;">This is what just took place in our
                                        area recently. The workshop included five languages from our region. Each of the
                                        five language teams brought six volunteers, ready to learn to be translators.
                                        Leaders trained the MAST method by Wycliffe Associates taught the process. They
                                        worked together for ten days on learning the process and translating Scripture.
                                        At the end of the workshop, all five languages completed the book of Mark and
                                        half of the book of John. They averaged almost 20 verses per translator per day
                                        during those 10 days! The translators were so excited with the work that some
                                        nights they had a hard time stopping to go to bed. The translators reported that
                                        they felt inadequate to do translation when they first arrived. But within a few
                                        days they were surprised at how much they had learned. By the end of the first
                                        week, they felt a confidence that they had never expected. And by the end of the
                                        workshop, they were ready, not only to work on the translation alone at home,
                                        but to share the process with others. These translators were overwhelmed by
                                        their accomplishments. One of them testified, “All glory to God for giving us a
                                        mind to know Him and understand his word. We came, just obeying Him, not
                                        thinking we could really do this. We are leaving, knowing that He has given us
                                        the wisdom and the desire to do this work. We are able to translate because of
                                        God’s Spirit working within us.” </p>
                                </div>
                            </div>
                            <div class="flex_middle editor_area" dir="ltr">
                                <div class="vnote verse_block" data-verse="5">
                                    <textarea name="chunks[4][name]" class="peer_verse_ta textarea verse_text_1"
                                              rows="1"
                                              style="overflow-x: hidden; overflow-wrap: break-word; height: 49px;">SOFIA</textarea>
                                    <textarea name="chunks[4][text]" class="peer_verse_ta textarea verse_text_2"
                                              style="overflow-x: hidden; overflow-wrap: break-word; min-height: 340px;">Esto es lo que ocurrió recientemente en nuestra área. El taller incluyó cinco idiomas de nuestra región. Cada uno de los cinco equipos de idiomas trajo seis voluntarios, listos para aprender a ser traductores. Los líderes entrenaron el método MAST por Wycliffe Associates enseñaron el proceso. Trabajaron juntos durante diez días para aprender el proceso y traducir las Escrituras. Al final del taller, los cinco idiomas completaron el libro de Marcos y la mitad del libro de Juan. ¡Promediaron casi 20 versos por traductor por día durante esos 10 días! Los traductores estaban tan entusiasmados con el trabajo que algunas noches les costaba mucho para irse a la cama. Los traductores informaron que se sintieron inadecuados para hacer la traducción cuando llegaron por primera vez. Pero a los pocos días se sorprendieron de lo mucho que habían aprendido. Al final de la primera semana, sintieron una confianza que nunca habían esperado. Y al final del taller, estaban listos, no solo para trabajar en la traducción solos en casa, sino para compartir el proceso con otros. Estos traductores quedaron abrumados por sus logros. Uno de ellos testificó: “Toda la gloria a Dios por darnos una mente para conocerlo y comprender su palabra. Vinimos, simplemente obedeciéndole, sin pensar que realmente podríamos hacer esto. Nos vamos, sabiendo que Él nos ha dado la sabiduría y el deseo de hacer este trabajo. Podemos traducir gracias al Espíritu de Dios que obra dentro de nosotros ".</textarea>
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false;
                                require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                        <div class="chunk_divider"></div>
                        <div class="flex_container chunk_block">
                            <div class="chunk_verses flex_left" dir="ltr">
                                <div class="verse_text" data-verse="6">
                                    <p class="verse_text_1" style="height: 49px;"><strong>LIAM</strong></p>
                                    <p class="verse_text_2" style="height: 220px;">Not only were the translators able to
                                        complete over 1,000 verses during the workshop, they also made a plan for
                                        continuing each project. The leaders on each team gathered together to decide
                                        how they could keep working on the translation once they went home. Some of the
                                        teams were made up of members who do not live in the same village. As you know,
                                        sometimes travel between villages can be difficult. These teams worked out plans
                                        for how to gather several times over the next few months so they can work
                                        together on translation. Each plan is unique because each team is unique and
                                        each language is unique. In this broadcast, we are going to follow one team from
                                        that workshop and see how their plan for completing the New Testament unfolds.
                                        We hope this will be an encouragement to you. I want to introduce you to a few
                                        of the translators who will be sharing their story with us on these weekly
                                        broadcasts.</p>
                                </div>
                            </div>
                            <div class="flex_middle editor_area" dir="ltr">
                                <div class="vnote verse_block" data-verse="6">
                                    <textarea name="chunks[5][name]" class="peer_verse_ta textarea verse_text_1"
                                              rows="1"
                                              style="overflow-x: hidden; overflow-wrap: break-word; height: 49px;">LIAM</textarea>
                                    <textarea name="chunks[5][text]" class="peer_verse_ta textarea verse_text_2"
                                              style="overflow-x: hidden; overflow-wrap: break-word; min-height: 220px;">Los traductores no solo pudieron completar más de 1,000 versos durante el taller, sino que también hicieron un plan para continuar cada proyecto. Los líderes de cada equipo se reunieron para decidir cómo podrían seguir trabajando en la traducción una vez que regresaran a casa. Algunos de los equipos estaban formados por miembros que no viven en la misma aldea. Como sabes, a veces viajar entre pueblos puede ser difícil. Estos equipos elaboraron planes sobre cómo reunirse varias veces en los próximos meses para que puedan trabajar juntos en la traducción. Cada plan es único porque cada equipo es único y cada idioma es único. En esta transmisión, seguiremos a un equipo de ese taller y veremos cómo se desarrolla su plan para completar el Nuevo Testamento. Esperamos que esto sea un estímulo para usted. Quiero presentarles a algunos de los traductores que compartirán su historia con nosotros en estas transmisiones semanales.</textarea>
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false;
                                require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                        <div class="chunk_divider"></div>
                        <div class="flex_container chunk_block">
                            <div class="chunk_verses flex_left" dir="ltr">
                                <div class="verse_text" data-verse="7">
                                    <p class="verse_text_1" style="height: 49px;"><strong>SOFIA</strong></p>
                                    <p class="verse_text_2" style="height: 360px;">Sammy is one of the leaders on this
                                        translation team. Sammy is in his early twenties, is married and has one child,
                                        a boy who is four years old. Sammy became a believer a few years ago through the
                                        witness of his wife. When his wife and sister heard about the translation
                                        workshop that was being arranged, they were both excited. They could hardly
                                        imagine what it would mean to their own community to have God’s Word in their
                                        mother tongue language. Both have friends and family members that do not believe
                                        in Jesus, and they feel like having Scripture will help them to witness to these
                                        loved ones. But they did not think they could go, because of having so many
                                        responsibilities at home. Sammy’s wife also felt uncertain about her ability in
                                        the national language. So, the two women suggested to Sammy that he gather some
                                        others together and go to the workshop. At first Sammy was very nervous for
                                        several reasons. As you can imagine, he did not think he knew enough to be a
                                        translator. He had only been a believer for a few years. Sammy’s father has
                                        rejected his faith and is outspoken against Christianity. How would he react to
                                        Sammy going to a translation event? Sammy also felt nervous about leaving his
                                        wife and son for 10 days. He wondered how all the work in his gardens would get
                                        done during that time. But he prayed about it and finally decided he needed to
                                        go. Although Sammy was nervous about the translation event, he became more
                                        excited as he invited other people to join. </p>
                                </div>
                            </div>
                            <div class="flex_middle editor_area" dir="ltr">
                                <div class="vnote verse_block" data-verse="7">
                                    <textarea name="chunks[6][name]" class="peer_verse_ta textarea verse_text_1"
                                              rows="1"
                                              style="overflow-x: hidden; overflow-wrap: break-word; height: 49px;">SOFIA</textarea>
                                    <textarea name="chunks[6][text]" class="peer_verse_ta textarea verse_text_2"
                                              style="overflow-x: hidden; overflow-wrap: break-word; min-height: 360px;">Sammy es uno de los líderes en este equipo de traducción. Sammy tiene poco más de veinte años, está casado y tiene un hijo, un niño de cuatro años. Sammy se convirtió en un creyente hace unos años a través del testimonio de su esposa. Cuando su esposa y su hermana se enteraron del taller de traducción que se estaba organizando, ambos estaban entusiasmados. Apenas podían imaginar lo que significaría para su propia comunidad tener la Palabra de Dios en su lengua materna. Ambos tienen amigos y familiares que no creen en Jesús, y sienten que tener las Escrituras les ayudará a testificar a estos seres queridos. Pero no pensaron que podrían ir, debido a que tenían tantas responsabilidades en casa. La esposa de Sammy también se sentía insegura sobre su habilidad en el idioma nacional. Entonces, las dos mujeres le sugirieron a Sammy que reuniera a otras personas y que fuera al taller. Al principio, Sammy estaba muy nervioso por varias razones. Como se puede imaginar, él no creía saber lo suficiente como para ser traductor. Solo había sido un creyente por unos años. El padre de Sammy ha rechazado su fe y es franco contra el cristianismo. ¿Cómo reaccionaría si Sammy fuera a un evento de traducción? Sammy también se sintió nervioso por dejar a su esposa e hijo durante 10 días. Se preguntó cómo se realizaría todo el trabajo en sus jardines durante ese tiempo. Pero oró al respecto y finalmente decidió que tenía que irse. Aunque Sammy estaba nervioso por el evento de traducción, se emocionó más cuando invitó a otras personas a unirse.</textarea>
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false;
                                require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                        <div class="chunk_divider"></div>
                        <div class="flex_container chunk_block">
                            <div class="chunk_verses flex_left" dir="ltr">
                                <div class="verse_text" data-verse="8">
                                    <p class="verse_text_1" style="height: 49px;"><strong>LIAM</strong></p>
                                    <p class="verse_text_2" style="height: 120px;">When Sammy got home with the copy of
                                        Mark that had been translated, his wife Siara was overjoyed to read it with him
                                        and give him some ideas. Sammy said at the end of the workshop that he plans to
                                        teach her the steps so she will be able to join the team and work with them.
                                        Siara is another character in our broadcasts. She very busy with a small artisan
                                        business and taking care of their four-year-old son. But she is excited to be
                                        part of the team and determined to make time for this very important work. </p>
                                </div>
                            </div>
                            <div class="flex_middle editor_area" dir="ltr">
                                <div class="vnote verse_block" data-verse="8">
                                    <textarea name="chunks[7][name]" class="peer_verse_ta textarea verse_text_1"
                                              rows="1"
                                              style="overflow-x: hidden; overflow-wrap: break-word; height: 49px;">LIAM</textarea>
                                    <textarea name="chunks[7][text]" class="peer_verse_ta textarea verse_text_2"
                                              style="overflow-x: hidden; overflow-wrap: break-word; min-height: 120px;">Cuando Sammy llegó a casa con la copia de Mark que había sido traducida, su esposa Siara se alegró de leerlo con él y darle algunas ideas. Sammy dijo al final del taller que planea enseñarle los pasos para que pueda unirse al equipo y trabajar con ellos. Siara es otro personaje en nuestras transmisiones. Ella estaba muy ocupada con un pequeño negocio artesanal y cuidando a su hijo de cuatro años. Pero está emocionada de ser parte del equipo y está decidida a hacer tiempo para este trabajo tan importante.</textarea>
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false;
                                require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                        <div class="chunk_divider"></div>
                        <div class="flex_container chunk_block">
                            <div class="chunk_verses flex_left" dir="ltr">
                                <div class="verse_text" data-verse="9">
                                    <p class="verse_text_1" style="height: 49px;"><strong>SOFIA</strong></p>
                                    <p class="verse_text_2" style="height: 120px;">Mara is Sammy’s younger sister. She
                                        became a believer from Sammy’s testimony. Her husband is not a believer, and
                                        neither is her father who lives with them. She found out about the translation
                                        workshop but could not go because of family obligations. Sammy promised he would
                                        teach her what he learned so she could participate in completing the New
                                        Testament. Mara has agreed to partner with Sammy in sharing their story in these
                                        broadcasts over the next several months.</p>
                                </div>
                            </div>
                            <div class="flex_middle editor_area" dir="ltr">
                                <div class="vnote verse_block" data-verse="9">
                                    <textarea name="chunks[8][name]" class="peer_verse_ta textarea verse_text_1"
                                              rows="1"
                                              style="overflow-x: hidden; overflow-wrap: break-word; height: 49px;">SOFIA</textarea>
                                    <textarea name="chunks[8][text]" class="peer_verse_ta textarea verse_text_2"
                                              style="overflow-x: hidden; overflow-wrap: break-word; min-height: 120px;">Mara es la hermana menor de Sammy. Se convirtió en una creyente del testimonio de Sammy. Su esposo no es creyente, y tampoco su padre, que vive con ellos. Ella se enteró del taller de traducción pero no pudo asistir debido a obligaciones familiares. Sammy le prometió que le enseñaría lo que aprendió para que ella pudiera participar en completar el Nuevo Testamento. Mara acordó asociarse con Sammy para compartir su historia en estas transmisiones durante los próximos meses.</textarea>
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false;
                                require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                        <div class="chunk_divider"></div>
                        <div class="flex_container chunk_block">
                            <div class="chunk_verses flex_left" dir="ltr">
                                <div class="verse_text" data-verse="10">
                                    <p class="verse_text_1" style="height: 49px;"><strong>LIAM</strong></p>
                                    <p class="verse_text_2" style="height: 160px;">Pastor Fin is Sammy, Siara and Mara’s
                                        pastor. He has a deep love for his community and a passion for God’s Word.
                                        Pastor Fin was skeptical when Sammy told him about the translation workshop. He
                                        did not think that average Christians from his community would know enough
                                        theology to translate God’s Word. He was concerned that the translation would be
                                        weak and inaccurate. But despite his concerns he agreed that Sammy should go.
                                        Pastor Fin was so moved when he heard Sammy read a portion of Mark aloud after
                                        returning from the workshop, that he is convinced MAST works and has become a
                                        leading member of the team. </p>
                                </div>
                            </div>
                            <div class="flex_middle editor_area" dir="ltr">
                                <div class="vnote verse_block" data-verse="10">
                                    <textarea name="chunks[9][name]" class="peer_verse_ta textarea verse_text_1"
                                              rows="1"
                                              style="overflow-x: hidden; overflow-wrap: break-word; height: 49px;">LIAM</textarea>
                                    <textarea name="chunks[9][text]" class="peer_verse_ta textarea verse_text_2"
                                              style="overflow-x: hidden; overflow-wrap: break-word; min-height: 160px;">El pastor Fin es el pastor de Sammy, Siara y Mara. Él tiene un profundo amor por su comunidad y una pasión por la Palabra de Dios. El pastor Fin se mostró escéptico cuando Sammy le contó sobre el taller de traducción. No creía que los cristianos promedio de su comunidad sabrían suficiente teología para traducir la Palabra de Dios. Le preocupaba que la traducción fuera débil e inexacta. Pero a pesar de sus preocupaciones, acordó que Sammy debería irse. El pastor Fin se conmovió tanto cuando escuchó a Sammy leer una parte de Mark en voz alta después de regresar del taller, que está convencido de que MAST trabaja y se ha convertido en un miembro líder del equipo.</textarea>
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false;
                                require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                        <div class="chunk_divider"></div>
                        <div class="flex_container chunk_block">
                            <div class="chunk_verses flex_left" dir="ltr">
                                <div class="verse_text" data-verse="11">
                                    <p class="verse_text_1" style="height: 49px;"><strong>SOFIA</strong></p>
                                    <p class="verse_text_2" style="height: 200px;">Jade is a friend of Siara’s that she
                                        met through her small artisan business. Jade is single and works as an executive
                                        at a bank in the city. She does not live in the same village as the others. She
                                        has gone to University and is fluent in the national language and very
                                        comfortable with technology. Jade was raised in a Christian home and became a
                                        believer as a child. Jade has worked hard to help women like Siara with small
                                        businesses in the village to expand their businesses into the city. She has a
                                        deep love for her people and her heritage. Jade went to the workshop and feels
                                        it is a great privilege to be part of this translation project. Her faith has
                                        been strengthened through working on translation. Since she lives in another
                                        town, she is planning to work with the translators remotely as much as possible
                                        until the translation is complete. </p>
                                </div>
                            </div>
                            <div class="flex_middle editor_area" dir="ltr">
                                <div class="vnote verse_block" data-verse="11">
                                    <textarea name="chunks[10][name]" class="peer_verse_ta textarea verse_text_1"
                                              rows="1"
                                              style="overflow-x: hidden; overflow-wrap: break-word; height: 49px;">SOFIA</textarea>
                                    <textarea name="chunks[10][text]" class="peer_verse_ta textarea verse_text_2"
                                              style="overflow-x: hidden; overflow-wrap: break-word; min-height: 200px;">Jade es una amiga de Siara que conoció a través de su pequeño negocio artesanal. Jade es soltera y trabaja como ejecutiva en un banco de la ciudad. Ella no vive en el mismo pueblo que los demás. Ella ha ido a la universidad y habla con fluidez el idioma nacional y se siente muy cómoda con la tecnología. Jade fue criada en un hogar cristiano y se convirtió en creyente de niña. Jade ha trabajado duro para ayudar a mujeres como Siara con pequeños negocios en el pueblo para expandir sus negocios en la ciudad. Ella tiene un profundo amor por su gente y su herencia. Jade fue al taller y siente que es un gran privilegio ser parte de este proyecto de traducción. Su fe se ha fortalecido al trabajar en la traducción. Como vive en otra ciudad, planea trabajar con los traductores de forma remota tanto como sea posible hasta que se complete la traducción.</textarea>
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false;
                                require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                        <div class="chunk_divider"></div>
                        <div class="flex_container chunk_block">
                            <div class="chunk_verses flex_left" dir="ltr">
                                <div class="verse_text" data-verse="12">
                                    <p class="verse_text_1" style="height: 49px;"><strong>LIAM</strong></p>
                                    <p class="verse_text_2" style="height: 200px;">The translation team is off to a
                                        great start. They completed the book of Mark and part of the book of John at
                                        their workshop. They have a plan for working on the rest of the New Testament.
                                        Their pastor, Pastor Fin, who was at first skeptical, now is fully supportive
                                        and a leader on the team. Sammy has plans to teach Mara and Siara what he
                                        learned so they can help with the work. Others who attended the workshop have
                                        committed to spending a couple of hours a week on translation. They have agreed
                                        to gather every few weeks for a period of checking. Over the next fifty-one
                                        broadcasts, this team will share with you their progress, challenges and
                                        successes. Here are the important topics we will cover. There are sixteen of
                                        topics, taking us through the whole MAST process from the beginning of the
                                        translation to celebrating its completion!</p>
                                </div>
                            </div>
                            <div class="flex_middle editor_area" dir="ltr">
                                <div class="vnote verse_block" data-verse="12">
                                    <textarea name="chunks[11][name]" class="peer_verse_ta textarea verse_text_1"
                                              rows="1"
                                              style="overflow-x: hidden; overflow-wrap: break-word; height: 49px;">LIAM</textarea>
                                    <textarea name="chunks[11][text]" class="peer_verse_ta textarea verse_text_2"
                                              style="overflow-x: hidden; overflow-wrap: break-word; min-height: 200px;">El equipo de traducción ha tenido un gran comienzo. Completaron el libro de Marcos y parte del libro de Juan en su taller. Tienen un plan para trabajar en el resto del Nuevo Testamento. Su pastor, el pastor Fin, que al principio era escéptico, ahora es totalmente solidario y un líder en el equipo. Sammy tiene planes de enseñarle a Mara y Siara lo que aprendió para que puedan ayudar con el trabajo. Otros que asistieron al taller se han comprometido a pasar un par de horas a la semana en traducción. Han acordado reunirse cada pocas semanas por un período de verificación. Durante las próximas cincuenta y una transmisiones, este equipo compartirá con usted su progreso, desafíos y éxitos. Estos son los temas importantes que cubriremos. ¡Hay dieciséis temas que nos llevan a través de todo el proceso MAST desde el comienzo de la traducción hasta la celebración de su finalización!</textarea>
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false;
                                require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                        <div class="chunk_divider"></div>
                        <div class="flex_container chunk_block">
                            <div class="chunk_verses flex_left" dir="ltr">
                                <div class="verse_text" data-verse="13">
                                    <p class="verse_text_1" style="height: 49px;"><strong>SOFIA</strong></p>
                                    <p class="verse_text_2" style="height: 200px;">During the year, we will hear about
                                        the reaction of the church to the translated Scripture. We will hear how it has
                                        impact beyond the church and makes a difference to the whole community. We will
                                        follow along as Sammy and his team meet the challenges of continuing their
                                        translation work at home. We will hear about the barriers and spiritual warfare
                                        and how the translation team addresses those challenges to completion of their
                                        goal. We will learn about how quality is assured in steps that are taken by the
                                        team as they develop a rubric and listen to input from the community. We will
                                        follow along with their ups and downs, their trials and triumphs. And we will
                                        finish the year in glorious celebration of what has been accomplished by their
                                        obedience to God’s call and their reliance on the guidance of the Holy
                                        Spirit.</p>
                                </div>
                            </div>
                            <div class="flex_middle editor_area" dir="ltr">
                                <div class="vnote verse_block" data-verse="13">
                                    <textarea name="chunks[12][name]" class="peer_verse_ta textarea verse_text_1"
                                              rows="1"
                                              style="overflow-x: hidden; overflow-wrap: break-word; height: 49px;">SOFIA</textarea>
                                    <textarea name="chunks[12][text]" class="peer_verse_ta textarea verse_text_2"
                                              style="overflow-x: hidden; overflow-wrap: break-word; min-height: 200px;">Durante el año, escucharemos acerca de la reacción de la iglesia a las Escrituras traducidas. Escucharemos cómo tiene un impacto más allá de la iglesia y hace una diferencia para toda la comunidad. Lo seguiremos mientras Sammy y su equipo enfrentan los desafíos de continuar su trabajo de traducción en casa. Escucharemos sobre las barreras y la guerra espiritual y cómo el equipo de traducción aborda esos desafíos para completar su objetivo. Aprenderemos sobre cómo se garantiza la calidad en los pasos que toma el equipo a medida que desarrollan una rúbrica y escuchan los comentarios de la comunidad. Seguiremos junto con sus altibajos, sus pruebas y triunfos. Y terminaremos el año en una gloriosa celebración de lo que se ha logrado mediante su obediencia al llamado de Dios y su confianza en la guía del Espíritu Santo.</textarea>
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false;
                                require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                        <div class="chunk_divider"></div>
                        <div class="flex_container chunk_block">
                            <div class="chunk_verses flex_left" dir="ltr">
                                <div class="verse_text" data-verse="14">
                                    <p class="verse_text_1" style="height: 49px;"><strong>LIAM</strong></p>
                                    <p class="verse_text_2" style="height: 200px;">I am so excited that we get to do
                                        these broadcasts, to learn from mother tongue translators, to hear their story
                                        and celebrate with them. It is amazing to think that their story could be the
                                        beginning of many similar stories. In your own town or village, you could begin
                                        a movement. You can be part of the team. You could be part of helping your
                                        community to get Scripture in their own language. What Sammy and Mara are doing
                                        is extraordinary. But it does not need to be uncommon. More than 1500 languages
                                        have portions of Scripture today because, by faith, members of their community
                                        joined a MAST workshop and faithfully continued their translation work. Like
                                        Sammy and Mara, translators are learning together and producing Scripture
                                        translation for their mother tongue community. You can be an important part of
                                        this!</p>
                                </div>
                            </div>
                            <div class="flex_middle editor_area" dir="ltr">
                                <div class="vnote verse_block" data-verse="14">
                                    <textarea name="chunks[13][name]" class="peer_verse_ta textarea verse_text_1"
                                              rows="1"
                                              style="overflow-x: hidden; overflow-wrap: break-word; height: 49px;">LIAM</textarea>
                                    <textarea name="chunks[13][text]" class="peer_verse_ta textarea verse_text_2"
                                              style="overflow-x: hidden; overflow-wrap: break-word; min-height: 200px;">Estoy tan emocionado de poder hacer estas transmisiones, aprender de los traductores de la lengua materna, escuchar su historia y celebrar con ellos. Es sorprendente pensar que su historia podría ser el comienzo de muchas historias similares. En su propia ciudad o pueblo, puede comenzar un movimiento. Puedes ser parte del equipo. Usted podría ser parte de ayudar a su comunidad a obtener las Escrituras en su propio idioma. Lo que Sammy y Mara están haciendo es extraordinario. Pero no tiene por qué ser poco común. Más de 1500 idiomas tienen porciones de las Escrituras hoy porque, por fe, los miembros de su comunidad se unieron a un taller MAST y continuaron fielmente su trabajo de traducción. Al igual que Sammy y Mara, los traductores están aprendiendo juntos y produciendo la traducción de las Escrituras para su comunidad de lengua materna. ¡Puedes ser una parte importante de esto!</textarea>
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false;
                                require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                        <div class="chunk_divider"></div>
                        <div class="flex_container chunk_block">
                            <div class="chunk_verses flex_left" dir="ltr">
                                <div class="verse_text" data-verse="15">
                                    <p class="verse_text_1" style="height: 49px;"><strong>SOFIA</strong></p>
                                    <p class="verse_text_2" style="height: 160px;">Let us close this first broadcast in
                                        prayer. Please join me. Dear Lord, Thank You for Your love for us and for Your
                                        gift of salvation. Thank you for the opportunity for us to have Scripture in our
                                        mother tongue language. We thank you for each person who is listening to the
                                        broadcast today. Please encourage them in their translation work if they already
                                        are part of a team. If they are not part of a team, please inspire them to
                                        pursue how they can help to make Scripture available in their mother tongue
                                        language. We ask your blessings on these broadcasts and all who listen to them
                                        throughout the year. Amen.</p>
                                </div>
                            </div>
                            <div class="flex_middle editor_area" dir="ltr">
                                <div class="vnote verse_block" data-verse="15">
                                    <textarea name="chunks[14][name]" class="peer_verse_ta textarea verse_text_1"
                                              rows="1"
                                              style="overflow-x: hidden; overflow-wrap: break-word; height: 49px;">SOFIA</textarea>
                                    <textarea name="chunks[14][text]" class="peer_verse_ta textarea verse_text_2"
                                              style="overflow-x: hidden; overflow-wrap: break-word; min-height: 160px;">Cerremos esta primera transmisión en oración. Por favor únete a mi. Querido Señor, gracias por tu amor por nosotros y por tu regalo de salvación. Gracias por la oportunidad para que tengamos las Escrituras en nuestro idioma materno. Agradecemos a cada persona que está escuchando la transmisión hoy. Aliéntelos en su trabajo de traducción si ya forman parte de un equipo. Si no forman parte de un equipo, inspírelos a buscar cómo pueden ayudar a que las Escrituras estén disponibles en su idioma materno. Les pedimos sus bendiciones en estas transmisiones y a todos los que las escuchan durante todo el año. Amén.</textarea>
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false;
                                require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                        <div class="chunk_divider"></div>
                        <div class="flex_container chunk_block">
                            <div class="chunk_verses flex_left" dir="ltr">
                                <div class="verse_text" data-verse="16">
                                    <p class="verse_text_1" style="height: 49px;"><strong>LIAM</strong></p>
                                    <p class="verse_text_2" style="height: 80px;">Now, until we meet again next time,
                                        receive the benediction from the book of Numbers, chapter 6, verses 24 through
                                        26: May Yahweh bless and keep you. May Yahweh make His face shine on you and be
                                        gracious to you. May Yahweh look on you with favor and give you peace.”
                                        Amen. </p>
                                </div>
                            </div>
                            <div class="flex_middle editor_area" dir="ltr">
                                <div class="vnote verse_block" data-verse="16">
                                    <textarea name="chunks[15][name]" class="peer_verse_ta textarea verse_text_1"
                                              rows="1"
                                              style="overflow-x: hidden; overflow-wrap: break-word; height: 49px;">LIAM</textarea>
                                    <textarea name="chunks[15][text]" class="peer_verse_ta textarea verse_text_2"
                                              style="overflow-x: hidden; overflow-wrap: break-word; height: 80px;">Ahora, hasta que nos volvamos a ver la próxima vez, reciba la bendición del libro de Números, capítulo 6, versículos 24 al 26: Que Yahweh los bendiga y los guarde. Que Yahweh haga que Su rostro brille sobre ti y sea amable contigo. Que Yahweh te mire con favor y te dé paz ”. Amén.</textarea>
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false;
                                require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                        <div class="chunk_divider"></div>
                        <div class="flex_container chunk_block">
                            <div class="chunk_verses flex_left" dir="ltr">
                                <div class="verse_text" data-verse="17">
                                    <p class="verse_text_1" style="height: 49px;"><strong>SOFIA</strong></p>
                                    <p class="verse_text_2" style="height: 80px;">Have a blessed week!</p>
                                </div>
                            </div>
                            <div class="flex_middle editor_area" dir="ltr">
                                <div class="vnote verse_block" data-verse="17">
                                    <textarea name="chunks[16][name]" class="peer_verse_ta textarea verse_text_1"
                                              rows="1"
                                              style="overflow-x: hidden; overflow-wrap: break-word; height: 49px;">SOFIA</textarea>
                                    <textarea name="chunks[16][text]" class="peer_verse_ta textarea verse_text_2"
                                              style="overflow-x: hidden; overflow-wrap: break-word; height: 80px;">¡Que tengas una semana bendecida!</textarea>
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false;
                                require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                        <div class="chunk_divider"></div>
                    </div>
                </div>

                <div class="main_content_footer row">
                    <div class="form-group">
                        <div class="main_content_confirm_desc"><?php echo __("confirm_finished") ?></div>
                        <label><input name="confirm_step" id="confirm_step" type="checkbox"
                                      value="1"/> <?php echo __("confirm_yes") ?></label>
                    </div>

                    <button id="next_step" type="submit" name="submit" class="btn btn-primary" disabled>
                        <?php echo __($data["next_step"]) ?>
                    </button>
                </div>
            </form>
            <div class="step_right alt"><?php echo __("step_num", ["step_number" => 4]) ?></div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps is_checker_page_help">
            <div class="help_name_steps">
                <span><?php echo __("step_num", ["step_number" => 4]) ?>: </span>
                <?php echo __("peer-review") ?>
            </div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("peer-review_rad_purpose") ?></li>
                    <li><?php echo __("self-check_help_2") ?></li>
                    <li><?php echo __("self-check_tn_help_2") ?></li>
                    <li><?php echo __("peer-review_checker_help_6") ?></li>
                    <li><?php echo __("peer-review_rad_help_4", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                    <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
                </ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more") ?></div>
            </div>
        </div>

        <div class="event_info is_checker_page_help">
            <div class="participant_info">
                <div class="additional_info">
                    <a href="/events/demo-rad/information"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="tutorial_container">
    <div class="tutorial_popup">
        <div class="tutorial-close glyphicon glyphicon-remove"></div>
        <div class="tutorial_pic">
            <img src="<?php echo template_url("img/steps/icons/peer-review.png") ?>" width="100" height="100"/>
            <img src="<?php echo template_url("img/steps/big/peer-review.png") ?>" width="280" height="280"/>

        </div>

        <div class="tutorial_content">
            <h3><?php echo __("peer-review") ?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("peer-review_rad_purpose") ?></li>
                <li><?php echo __("self-check_help_2") ?></li>
                <li><?php echo __("self-check_tn_help_2") ?></li>
                <li><?php echo __("peer-review_checker_help_6") ?></li>
                <li><?php echo __("peer-review_rad_help_4", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
            </ul>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#next_step").click(function (e) {
            e.preventDefault();
            window.location.href = '/events/demo-rad/pray';
            return false;
        });
    });
</script>