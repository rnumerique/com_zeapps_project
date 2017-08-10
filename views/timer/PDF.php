<html>
<header>
    <style>
        body {
            font-family: Verdana;
            font-size: 12px;
        }

        table {
            width: 100%;
            margin: 10px 0;
            background-color: #ffffff;
            border-collapse: collapse;
        }
        td{
            vertical-align: top;
            border-top: 1px solid #c7c7c7;
        }
        .title{
            background-color: #c7c7c7;
        }
        .lines th,
        .lines td,
        .taxes th,
        .taxes td,
        .total th,
        .total td,
        .text-left {
            text-align: left;
        }
        .text-center {
            text-align: center;
        }
        .text-right{
            text-align: right;
        }
    </style>
</header>
<body>
<table class="root">
    <tr>
        <td colspan="2">
            <table class="lines">
                <thead>
                <tr>
                    <th class="text-left">Tâche</th>
                    <th class="text-left">Utilisateur</th>
                    <th class="text-center">Durée</th>
                    <th class="text-right">Début</th>
                    <th class="text-right">Fin</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if(isset($timers)) {
                    foreach ($timers as $timer) {
                        ?>
                        <tr>
                            <td class="text-left">
                                <?php echo $timer->label; ?>
                            </td>
                            <td class="text-left">
                                <?php echo $timer->name_user; ?>
                            </td>
                            <td class="text-center">
                                <?php echo $timer->time_spent_formatted; ?>
                            </td>
                            <td class="text-right">
                                <?php echo date('d/m/Y H:i', strtotime($timer->start_time)); ?>
                            </td>
                            <td class="text-right">
                                <?php echo date('d/m/Y H:i', strtotime($timer->stop_time)); ?>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
                </tbody>
            </table>
        </td>
    </tr>
</table>
</body>
</html>