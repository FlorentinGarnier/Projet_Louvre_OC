/*
Add visitor form on click
 */

    $(document).ready(function() {
        // On récupère la balise <div> en question qui contient l'attribut « data-prototype » qui nous intéresse.
        var $container = $('div.booking_visitors');

        // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
        var index = $container.find(':input').length;

        if ($container.find(':input')) {
            $()
        }

        // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
        $('#add_form').click(function(e) {
            addVisitor($container);

            e.preventDefault(); // évite qu'un # apparaisse dans l'URL
            return false;
        });

        // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un.
        if (index == 0) {
            addVisitor($container);
        } else {
            // S'il existe déjà des catégories, on ajoute un lien de suppression pour chacune d'entre elles
            $container.children('div').each(function() {
                addDeleteLink($(this));
            });
        }

        // La fonction qui ajoute un formulaire Visitor
        function addVisitor($container) {
            // Dans le contenu de l'attribut « data-prototype », on remplace :
            // - le texte "__name__label__" qu'il contient par le label du champ
            // - le texte "__name__" qu'il contient par le numéro du champ
            var template = $container.attr('data-prototype')
                    .replace(/__name__label__/g, 'Visiteur n° ' + (index+1))
                    .replace(/__name__/g, index)
                ;


            // On crée un objet jquery qui contient ce template
            var $prototype = $(template).addClass('well visitor');

            // On ajoute au prototype un lien pour pouvoir supprimer la catégorie
            if (index != 0){

                addDeleteLink($prototype);
            }

            // On ajoute le prototype modifié à la fin de la balise <div>
            $container.append($prototype);

            // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
            index++;
        }

        // La fonction qui ajoute un lien de suppression d'une catégorie
        function addDeleteLink($prototype) {
            // Création du lien
            var $deleteLink = $('<a href="#" class="text-danger boutonFermeture"><span class="glyphicon glyphicon-remove"</span></a>');

            // Ajout du lien
            $prototype.prepend($deleteLink);

            // Ajout du listener sur le clic du lien pour effectivement supprimer la catégorie
            $deleteLink.click(function(e) {
                $prototype.remove();

                e.preventDefault(); // évite qu'un # apparaisse dans l'URL
                return false;
            });
        }

        /*
         Datepicker booking
         */

        $('#booking_visit_date').datepicker({
            monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            monthNamesShort: ['Jan', 'Fév', 'Mar', 'Avr', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
            dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
            dayNamesShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
            dayNamesMin: ['Di', 'Lu', 'Mar', 'Me', 'Je', 'Ve', 'Sa'],
            weekHeader: 'Sm',
            firstDay: 1,
            dateFormat: 'yy-mm-dd',
            minDate: 0,
            beforeShowDay: function (date) {
                return [!(date.getDay() == 0 ||
                date.getDay() == 2 ||
                (date.getMonth() == 0 && date.getDate() == 1) ||
                (date.getMonth() == 4 && date.getDate() == 1) ||
                (date.getMonth() == 4 && date.getDate() == 8) ||
                (date.getMonth() == 6 && date.getDate() == 14) ||
                (date.getMonth() == 7 && date.getDate() == 15) ||
                (date.getMonth() == 10 && date.getDate() == 1) ||
                (date.getMonth() == 10 && date.getDate() == 11) ||
                (date.getMonth() == 11 && date.getDate() == 25))];
            },
            onSelect: function (date) {
                var today = new Date();
                var selectDate = new Date(date);
                $('#booking_half_day').prop('disabled', false).prop('checked', false);
                $('#halfday').html('');
                $('#hiddenHD').remove();
                if (selectDate.getDay() === today.getDay() &&
                    selectDate.getDate() === today.getDate() &&
                    selectDate.getYear() === today.getYear()) {
                    if (today.getHours() >= 14) {
                        $('#booking_half_day')
                            .prop('checked', true)
                            .prop('disabled', true)
                            .offsetParent().append('<input type="hidden" id="hiddenHD" name="booking[half_day]" value="1">');
                        $('#halfday').html('Après 14h00, vous pouvez uniquement commander un billet demi-journée pour une visite le jour même');
                    }


                }
            }
        });

        $('body').on('focus', ".birthday", function () {
            $(this).datepicker({
                monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
                monthNamesShort: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
                dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
                dayNamesShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
                dayNamesMin: ['Di', 'Lu', 'Mar', 'Me', 'Je', 'Ve', 'Sa'],
                weekHeader: 'Sm',
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                yearRange: "-150:+0"
            })


        });


        /*

         Check of available places
         */
        function check(date) {
            $.ajax({
                url: "app_dev.php/preCheckIn",
                method: "POST",
                data: {booking_date: date}
            }).done(function (message) {
                if (JSON.parse(message['status']) === 0) {
                    $('#add_form').prop('disabled', true);
                }
            })
        }

        $('#add_form').click(function (e) {
                e.preventDefault();
                check('25-07-2016') //TODO
            }
        )



    });
