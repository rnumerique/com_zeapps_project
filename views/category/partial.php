<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<table class="table table-condensed table-stripped">
    <thead>
    <tr>
        <th>Catégorie</th>
        <th class="text-right">
            <a class='btn btn-xs btn-success' ng-href='/ng/com_zeapps_project/project/categories/create/{{ project.id }}'><span class='fa fa-plus' aria-hidden='true'></span> Categorie</a>
        </th>
    </tr>
    </thead>
    <tbody>
    <tr ng-repeat="category in categories">
        <td>{{ category.title }}</td>
        <td class="text-right">
            <button type="button" class="btn btn-info btn-xs" ng-click="editCategory(category)">
                <i class="fa fa-fw fa-pencil" ></i>
            </button>
            <button type="button" class="btn btn-danger btn-xs" ng-click="deleteCategory(category)">
                <i class="fa fa-fw fa-trash" ></i>
            </button>
        </td>
    </tr>
    </tbody>
</table>