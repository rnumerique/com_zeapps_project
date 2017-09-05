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
        table.lines td{
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
        <td>
            <table class="lines">
                <thead>
                <tr>
                    <th class="text-left">#</th>
                    <th class="text-left">Catégorie</th>
                    <th class="text-left">Priorité</th>
                    <th class="text-left">To-Do</th>
                    <th>Demandeur</th>
                    <th>Assigné à</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if(isset($dates) && isset($cardsByDate)) {
                    foreach ($dates as $date) {
                        if(sizeof($cardsByDate[$date->due_date]) > 0) {
                            ?>
                            <tr class="title">
                                <td colspan="6">
                                    <?php echo $date->due_date !== '0000-00-00' ? date('d m Y', strtotime($date->due_date)) : 'Sans date attribuée'; ?>
                                    (<?php echo sizeof($cardsByDate[$date->due_date]); ?>)
                                </td>
                            </tr>
                            <?php
                            if ($cardsByDate[$date->due_date]) {
                                foreach ($cardsByDate[$date->due_date] as $card) {
                                    ?>
                                    <tr>
                                        <td><?php echo $card->id; ?></td>
                                        <td<?php if($card->color){ ?> style="background-color: <?php echo $card->color; ?>"<?php } ?>>
                                            <?php echo $card->category_title; ?>
                                        </td>
                                        <td<?php if($card->priority_color){ ?> style="background-color: <?php echo $card->priority_color; ?>"<?php } ?>>
                                            <?php echo $card->priority; ?>
                                        </td>
                                        <td>
                                            <?php
                                            echo htmlspecialchars($card->title);
                                            if ($description) {
                                                echo "\n";
                                                echo htmlspecialchars($card->description);
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo $card->name_manager; ?></td>
                                        <td><?php echo $card->name_assigned_to; ?></td>
                                    </tr>
                                    <?php
                                }
                            }
                        }
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