/**
 * Created by TI on 10/04/2018.
 */
function checkPermissions(){

    if(perm_btn['add']==0)
    {
        $("#grid .k-grid-add").hide();
        $("#grid .k-grid-nuevo").hide();
    }

    var grid = $("#grid").data("kendoGrid");
    var gridData = grid.dataSource.view();

    for (var i = 0; i < gridData.length; i++) {
        var currentUid = gridData[i].uid;
        var currenRow = grid.table.find("tr[data-uid='" + currentUid + "']");
        var editButton = $(currenRow).find(".k-grid-edit");
        var deleteButton = $(currenRow).find(".k-grid-Eliminar");
        if(perm_btn['update']==0 || perm_btn['update']==null)
        {
           editButton.hide();
           $(currenRow).find(".k-grid-Editar").hide();
        }
        if(perm_btn['delete']==0 || perm_btn['delete']==null)
           deleteButton.hide();
    }


}
function functiontofindIndexByKeyValue(arraytosearch, key, valuetosearch) {

    for (var i = 0; i < arraytosearch.length; i++) {

        if (arraytosearch[i][key] == valuetosearch) {
            return i;
        }
    }
    return null;
}
function deleteRow(row,array,id)
{
    if(array)
    {
        if(id)
        {
            var index = array.map(function(x) {return x.id; }).indexOf(id);
            array.splice(index,1);
        }
        else
        {
            var id= $(row).parent().parent().parent().attr('id');
            var index = farray.map(function(x) {return x.id; }).indexOf(id);
            array.splice(index,1);
        }
    }
    $(row).parent().parent().remove();
}
function disabledBtn(classbtn,flag)
{
    $("."+classbtn).prop('disabled',flag);
}
function onChange(arg) {
    selected = this.selectedKeyNames().join(", ");
}
function onClose() {
    undo.fadeIn();
}
function adjustDropDownWidth(e) {
    var listContainer = e.sender.list.closest(".k-list-container");
    listContainer.width(listContainer.width() + kendo.support.scrollbar());
  }    
//Estatus catalogos
var status_array = new Array();
status_array.push({value:"activo",text:"Activo"});
status_array.push({value:"inactivo",text:"Inactivo"});

//Valores Si/No
var option_array = new Array();
option_array.push({value:"No",text:"No"});
option_array.push({value:"Si",text:"Si"});

//SELECT2 CASCADE
/**
 * A Javascript module to loadeding/refreshing options of a select2 list box using ajax based on selection of another select2 list box.
 *
 * @url : https://gist.github.com/ajaxray/187e7c9a00666a7ffff52a8a69b8bf31
 * @auther : Anis Uddin Ahmad <anis.programmer@gmail.com>
 *
 * Live demo - https://codepen.io/ajaxray/full/oBPbQe/
 * w: http://ajaxray.com | t: @ajaxray
 */
var Select2Cascade = ( function(window, $) {

    function Select2Cascade(parent, child, url, select2Options) {
        var afterActions = [];
        var options = select2Options || {};

        // Register functions to be called after cascading data loading done
        this.then = function(callback) {
            afterActions.push(callback);
            return this;
        };

        parent.select2(select2Options).on("change", function (e) {

            child.prop("disabled", true);
            var _this = this;

            $.getJSON(url.replace(':parentId:', $(this).val()), function(items) {
                var newOptions = '<option value="">-- Select --</option>';
                for(var id in items) {
                    newOptions += '<option value="'+ id +'">'+ items[id] +'</option>';
                }

                child.select2('destroy').html(newOptions).prop("disabled", false)
                    .select2(options);

                afterActions.forEach(function (callback) {
                    callback(parent, child, items);
                });
            });
        });
    }

    return Select2Cascade;

})( window, $);

function buttosAction(value) {
    return '<button class="btn btn-warning btn-xs btn-edit" onclick="editar()" ><i class="fa fa-pencil"></i> </button>'+
    '<button class="btn btn-danger btn-xs btn-delete" onclick="borrar()" ><i class="fa fa-trash"></i> </button>';
}
function buttonDelete(value) {
    return '<button class="btn btn-danger btn-xs btn-delete" ><i class="fa fa-trash"></i> Eliminar</button>';
}
function checkboxStatus(value)
{
    var checked = (value=="1" ? "checked='checked'" : "");
    return '<input type="checkbox" '+checked+' value="'+value+'" />';
}
