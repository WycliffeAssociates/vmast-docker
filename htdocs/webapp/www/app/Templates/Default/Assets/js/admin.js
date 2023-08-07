/**
 * Created by Maxim on 05 Feb 2016.
 */

// ------------------ Jquery Events --------------------- //

var importLocked = false;

$(function () {

    if(typeof $.fn.chosen == "function")
        $("#subGwLangs, #targetLangs, "
            + "#sourceTranslation, #gl_admins, #project_admins, "
            + "#sourceTranslationNotes, "
            + "#gwLang, #projectMode, "
            + "#src_language, #src_type, #src")
            .chosen();

    // Open gateway language form
    $("#cregl").click(function () {
        $("#gatewayLanguage").trigger("reset");
        $(".errors").html("");
        $(".main-content").css("left", 0);
    });

    $(".panel-close").click(function() {
        $(this).parents(".form-panel").css("left", "-9999px");
    });

    // Submit gateway language form
    $("#gatewayLanguage").submit(function(e) {

        $.ajax({
            url: $("#gatewayLanguage").prop("action"),
            method: "post",
            data: $("#gatewayLanguage").serialize(),
            dataType: "json",
            beforeSend: function() {
                $(".gatewayLanguageLoader").show();
            }
        })
            .done(function(data) {
                if(data.success)
                {
                    $(".form-panel").css("left", "-9999px");

                    renderPopup(data.success, function () {
                        location.reload();
                    });
                }
                else
                {
                    $(".errors").html(data.error);
                }
            })
            .always(function() {
                $(".gatewayLanguageLoader").hide();
            });

        e.preventDefault();
    });

    $(".gl_edit").click(function () {
        var id = $(this).data("id");

        $.ajax({
            url: "/admin/rpc/get_gl_admins",
            method: "post",
            data: {glID: id},
            dataType: "json",
            beforeSend: function() {
                $(this).prop("disabled", true);
                $("#gl_admins").val('').trigger("chosen:updated");
            }
        }).done(function(data) {
            if(data.success)
            {
                var content = "";
                $.each(data.admins, function(k, v) {
                    content += '' +
                        '<option value="' + k + '" selected>' + v + '</option>';
                });
                $("#gl_admins").prepend(content);

                $("#glID").val(id);
                $(".admins-content").css("left", 0);
            }
            else
            {
                renderPopup(data.error);
            }
        }).always(function() {
            $(this).prop("disabled", false);
            $("#gl_admins").trigger("chosen:updated");
        });
    });


    $("#gatewayLanguageAdmins").submit(function (e) {
        $.ajax({
            url: $("#gatewayLanguageAdmins").prop("action"),
            method: "post",
            data: $("#gatewayLanguageAdmins").serialize(),
            dataType: "json",
            beforeSend: function() {
                $(".gatewayLanguageLoader").show();
            }
        })
            .done(function(data) {
                if(data.success)
                {
                    location.reload();
                }
                else
                {
                    renderPopup(data.error);
                }
            })
            .always(function() {
                $(".gatewayLanguageLoader").hide();
            });

        e.preventDefault();
    });


    $("select[name=projectMode]").change(function() {
        $("#projectType").val('').trigger("chosen:updated");

        showProjectTools();

        if(["bible","odb","rad"].indexOf($(this).val()) > -1)
        {
            $("#sourceTools").val('').trigger("chosen:updated");
            $(".sourceTools").addClass("hidden");
            $(".projectType").removeClass("hidden");
            if(["odb","rad"].indexOf($(this).val()) > -1)
            {
                $(".sourceTranslation").addClass("hidden");
                hideProjectTools();
                if($(this).val() == "rad") $(".projectType").addClass("hidden");
            }
            else
            {
                $(".sourceTranslation").removeClass("hidden");
            }
        }
        else if(["tn","tq","tw","obs","bc","bca"].indexOf($(this).val()) > -1)
        {
            const project = $(this).val() === "bca" ? "bc" : $(this).val();

            $(".projectType").addClass("hidden");
            $(".sourceTranslation").removeClass("hidden");
            $(".sourceTools").removeClass("hidden");
            $(".sourceTools label").text(Language["book_" + project]);
            $("#sourceTools").attr("data-placeholder", Language["chooseSource_" + project]);
            $("#sourceTools").chosen().trigger("chosen:updated");
            hideProjectTools();
        }
    });

    $("#crepr").click(function () {
        resetProjectForm();
        $(".sub-content").css("left", 0);
    });


    $(".editProject").click(function() {
        var projectID = $(this).data("projectid");
        resetProjectForm();

        $.ajax({
            url: "/admin/rpc/get_project",
            method: "post",
            data: {projectID: projectID},
            dataType: "json",
            beforeSend: function() {
                $(".editProject").prop("disabled", true);
            }
        })
            .done(function(data) {
                if(data.success)
                {
                    $("button[name=project]").text(Language.save);
                    $("#projectAction").val("edit");

                    setProjectForm(data);

                    $(".sub-content").css("left", 0);
                }
                else
                {
                    if(typeof data.error != "undefined")
                    {
                        if(data.error == "login" || data.error == "admin")
                            window.location.href = "/members/login";
                        else
                        {
                            renderPopup(data.error);
                        }
                    }
                }
            })
            .always(function() {
                $(".editProject").prop("disabled", false);
                $("#project_admins").trigger("chosen:updated");
            });
    });

    // Get list of target languages for selected gateway language
    $("#subGwLangs").change(function() {
        var tlOptions = "<option value=''></option>";

        if($(this).val() === "") {
            $("#targetLangs").html(tlOptions);
            return;
        }

        $.ajax({
                url: "/admin/rpc/get_all_languages",
                method: "post",
                data: {gwLang: $("#subGwLangs").val()},
                dataType: "json",
                beforeSend: function() {
                    $(".subGwLoader").show();
                }
            })
            .done(function(targetLangs) {
                if(targetLangs.length <= 0) return false;

                $.each(targetLangs, function (i, v) {
                    tlOptions += '<option value="'+ v.langID+'">'+
                        '['+v.langID+'] '+v.langName+(v.angName !== "" && v.langName !== v.angName ? ' ( '+v.angName+' )' : '')+
                    '</option>';
                });
                $("#targetLangs").html(tlOptions);
                $("#project select").trigger("chosen:updated");
                document.dispatchEvent(new Event("target-langs-updated"));
            })
            .always(function() {
                $(".subGwLoader").hide();
            });
    });

    // Submit project form
    $("#project").submit(function(e) {
        $.ajax({
                url: $("#project").prop("action"),
                method: "post",
                data: $("#project").serialize(),
                dataType: "json",
                beforeSend: function() {
                    $(".projectLoader").show();
                }
            })
            .done(function(data) {
                if(typeof data.login != "undefined")
                {
                    location.reload();
                    return false;
                }

                if(data.success)
                {
                    $(".form-panel").css("left", "-9999px");

                    renderPopup(data.success, function () {
                        location.reload();
                    });
                }
                else
                {
                    $(".subErrors").html(data.error);
                }
            })
            .always(function() {
                $(".projectLoader").hide();
            });

        e.preventDefault();
    });


    // Event options
    if($().ajaxChosen)
    {
        $("#adminsSelect, #gl_admins, #project_admins").ajaxChosen({
                type: 'post',
                url: '/admin/rpc/get_members',
                dataType: 'json',
                minTermLength: 1,
                afterTypeDelay: 500,
                jsonTermKey: "search",
                lookingForMsg: Language.searchingFor,
            },
            function (data)
            {
                var terms = {};

                $.each(data, function (i, val) {
                    terms[i] = val;
                });

                return terms;
            },
            {
                no_results_text: Language.noResultText
            });
    }

    // Open event form
    $(".startEvnt").click(function() {
        $(".event-content").css("left", 0);

        resetEventForm();

        var bookCode = $(this).data("bookcode");
        var bookName = $(this).data("bookname");
        var chaptersNum = $(this).data("chapternum");
        var bookProject = $("#bookProject").val();

        if(["tn","tq","tw"].indexOf(bookProject) > -1) {
            $(".event_imports").show();
        } else if(bookProject === "ulb") {
            $(".input_mode_group").show();
            $(".revision_mode_group").hide();
        }

        $(".bookName").text(bookName);
        $("#bookCode").val(bookCode);

        $(".book_info_content").html(
            '(<strong>'+Language.chaptersNum+':</strong> '+chaptersNum+')'
        );
    });


    // Submit event form
    $("#startEvent").submit(function(e) {
        $.ajax({
                url: $(this).prop("action"),
                method: "post",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend: function() {
                    $(".startEventLoader").show();
                }
            })
            .done(function(data) {
                if(data.success)
                {
                    location.reload();
                }
                else
                {
                    $(".errors").html(data.error);
                }
            })
            .always(function() {
                $(".startEventLoader").hide();
            });

        e.preventDefault();
    });

    // Edit event form
    $(".editEvnt").click(function () {
        resetEventForm();

        var bookCode = $(this).data("bookcode");
        var eventID = $(this).data("eventid");
        var sort = $(this).data("sort");
        var bookProject = $("#bookProject").val();

        $("#eID").val(eventID);
        $("#sort").val(sort);
        $("#bookCode").val(bookCode);

        $("#eventAction").val("edit");
        $(".event_menu").show();

        if(["tn","tq","tw"].indexOf(bookProject) > -1) {
            $(".event_imports").show();
        }

        $.ajax({
            url: "/admin/rpc/get_event",
            method: "post",
            data: {eventID: eventID},
            dataType: "json",
            beforeSend: function() {
                $(".editEvnt").prop("disabled", true);
            }
        })
            .done(function(data) {
                if(data.success)
                {
                    $(".input_mode").prop("disabled", true);
                    $(".input_mode[value="+data.event.inputMode+"]").prop("checked", true);
                    $(".revision_mode").prop("disabled", true);
                    $(".revision_mode[value="+data.event.revisionMode+"]").prop("checked", true);

                    setImportComponent(data.event);
                    setEventMenu(data.event);
                    setStartEventButton(data.event);

                    // Set the status of ulb translation
                    setImportLinksUlb(data);

                    $(".bookName").text(data.event.book_info.name);
                    $(".book_info_content").html(
                        '(<strong>'+Language.chaptersNum+':</strong> '+data.event.book_info.chaptersNum+')'
                    );

                    var admins = data["admins"];
                    var content = "";
                    $.each(admins, function(k, v) {
                        content += '' +
                            '<option value="' + k + '" selected>' + v + '</option>';
                    });

                    $("#adminsSelect").prepend(content);
                    $(".event-content").css("left", 0);
                }
                else
                {
                    if(typeof data.error != "undefined")
                    {
                        if(data.error === "login" || data.error === "admin")
                            window.location.href = "/members/login";
                        else
                        {
                            renderPopup(data.error);
                        }
                    }
                }
            })
            .always(function() {
                $(".editEvnt").prop("disabled", false);
                $("#adminsSelect").trigger("chosen:updated");
            });
    });

    $(".event_menu li").click(function() {
        const id = $(this).data("id");
        $(".main_menu ul").hide();

        switch (id) {
            case "clearCache":
                clearCache();
                break;
            case "deleteEvent":
                $(".delinput").show();
                break;
        }
    });

    function clearCache() {
        var sort = $("#sort").val();
        var bookCode = $("#bookCode").val();
        var sourceLangID = $("#sourceLangID").val();
        var sourceBible = $("#sourceBible").val();

        $.ajax({
            url: "/admin/rpc/clear_cache",
            method: "post",
            data: {
                sort: sort,
                bookCode: bookCode,
                sourceLangID: sourceLangID,
                sourceBible: sourceBible
            },
            dataType: "json",
            beforeSend: function() {
                $(".startEventLoader").show();
            }
        })
            .done(function(data) {
                if(data.success)
                {
                    renderPopup(Language.cacheUpdated);
                }
                else
                    renderPopup(Language.commonError, function () {
                        window.location.reload();
                    }, function () {
                        window.location.reload();
                    });
            })
            .always(function() {
                $(".startEventLoader").hide();
            });
    }

    $("button[name=deleteEvent]").click(function (e) {
        var bookName = $(".bookName").text();
        var delName = $("#delevnt").val();

        if($(".delinput").is(":visible") && bookName == delName)
            $("#eventAction").val("delete");
        else
            e.preventDefault();
    });

    $("input[name=eventLevel]").change(function () {
        var level = $("input[name=eventLevel]:checked").val();
        var initialLevel = $("#initialLevel").val();
        var bookProject = $("#bookProject").val();
        var eventID = $("#eID").val();

        if(level > initialLevel || eventID == "0")
        {
            $("button[name=startEvent]").text(Language.create);
            $("#eventAction").val("create");
        }
        else
        {
            $("button[name=startEvent]").text(Language.save);
            $("#eventAction").val("edit");
        }

        $(".revision_mode_group").hide();
        $(".input_mode_group").hide();
        $(".event_imports").hide();

        switch (level) {
            case "1":
                $(".input_mode_group").slideDown(200);
                break;
            case "2":
                if(["ulb","udb","sun"].indexOf(bookProject) > -1)
                {
                    $(".l1_import").show();
                    $(".l2_import").hide();
                    $(".event_imports").slideDown(200);
                    $(".revision_mode_group").slideDown(200);
                    $(".revision_mode").prop("disabled", false);
                }
                else if(["tn","tq","tw"].indexOf(bookProject) > -1)
                {
                    $("."+bookProject+"_l1_import").show();
                    $("."+bookProject+"_l2_import").hide();
                    $(".l2_import").hide();
                    $(".l3_import").hide();
                    $(".event_imports").slideDown(200);
                }
                break;
            case "3":
                if(["ulb","udb","sun"].indexOf(bookProject) > -1)
                {
                    $(".l1_import").hide();
                    $(".l2_import").show();
                }
                else if(["tn","tq","tw"].indexOf(bookProject) > -1)
                {
                    $("."+bookProject+"_l1_import").hide();
                    $("."+bookProject+"_l2_import").show();
                    $(".l2_import").show();
                    $(".l3_import").show();
                }
                $(".event_imports").slideDown(200);
                break;
        }
    });

    $(".import_link").click(function (e) {
        var source = $(this).data("source");
        var bookProject = $("#bookProject").val();

        switch (source) {
            case "tq_l1":
            case "tq_l2":
            case "tn_l1":
            case "tn_l2":
            case "tw_l1":
            case "tw_l2":
                $("li[data-type=usfm]").hide();
                $("li[data-type=ts]").show();
                $("li[data-type=zip]").show();

                $("#importLevel").val(source.replace( /^\D+/g, ''));
                $("#importProject").val(bookProject);
                break;

            case "l1":
            case "l2":
            case "l3":
                if(["tn","tq","tw"].indexOf(bookProject) > -1)
                {
                    $("#importProject").val("ulb");

                    if(source == "l3")
                    {
                        var l2_import = $(".l2_import .import_done");
                        if(!l2_import.hasClass("done") || !l2_import.is(":visible"))
                        {
                            renderPopup(Language.import_l2_warning);
                            return;
                        }
                    }
                }

                $("#importLevel").val(source.replace( /^\D+/g, ''));

                $("li[data-type=usfm]").show();
                $("li[data-type=ts]").show();
                $("li[data-type=zip]").show();
                break;
        }

        $(".event-content").css("left", -9000);
        $(".import_menu_content").css("left", 0);

        e.preventDefault();
    });

    $(".import_menu ul li:last-child").click(function () {
        if(importLocked) return false;
        $(".import_menu_content").css("left", -9000);
        $(".event-content").css("left", 0);
    });

    $(".repo_server_import_menu ul li:last-child").click(function () {
        if(importLocked) return false;
        $(".repo_server_import_menu_content").css("left", -9000);
        $(".import_menu_content").css("left", 0);
    });

    $(".import_menu label").click(function () {
        if(importLocked) return false;
        return true;
    });

    $("#repo_server_form").submit(function (e) {
        e.preventDefault();
        return false;
    });

    $(".import_menu input[name=import]").change(function () {
        if(importLocked) return false;

        var input = $(this);
        var form = $(this).parents("form");
        var formData = null;
        var projectID = $("#projectID").val();
        var eventID = $("#eID").val();
        var bookCode = $("#bookCode").val();
        var bookProject = $("#bookProject").val();
        var importLevel = $("#importLevel").val();
        var importProject = $("#importProject").val();

        if (window.FormData){
            formData = new FormData(form[0]);
            formData.append("projectID", projectID);
            formData.append("eventID", eventID);
            formData.append("bookCode", bookCode);
            formData.append("bookProject", bookProject);
            formData.append("importLevel", importLevel);
            formData.append("importProject", importProject);
        }

        $.ajax({
            url         : '/admin/rpc/import',
            data        : formData ? formData : form.serialize(),
            cache       : false,
            contentType : false,
            processData : false,
            type        : 'POST',
            dataType    : "json",
            beforeSend: function() {
                importLocked = true;
                $(".importLoader").show();
            }
        })
            .done(function(response) {
                if(response.success)
                {
                    if(typeof response.warning != "undefined")
                        renderPopup(response.message + " " + "(with warning: We couldn't define the related scripture. Contact administrator.)");

                    if(["tn","tq","tw"].indexOf(importProject) > -1)
                    {
                        if(importLevel == 1)
                        {
                            $(".import_done", $(".import."+importProject+"_l1_import")).hide();
                            $(".import_progress", $(".import."+importProject+"_l1_import")).show();
                            window.location.reload();
                        }
                        else
                            $(".import_done", $(".import."+importProject+"_l2_import")).addClass("done");
                    }
                    else
                        $(".import_done", $(".import.l"+importLevel+"_import")).addClass("done");

                    $(".import_menu_content").css("left", -9000);
                    $(".event-content").css("left", 0);
                }
                else
                {
                    renderPopup(response.error);
                }
            })
            .always(function() {
                input.val("");
                importLocked = false;
                $(".importLoader").hide();
            });
    });

    $(".import_menu ul li").click(function () {
        const type = $(this).data("type");
        if(type === "dcs" || type === "wacs") {
            let title = Language.importFromRepo.formatUnicorn({
                "repo_server": type.toUpperCase()
            });
            $(".repo_server_title").text(title);
            $(".repo_server_list tbody").html("");
            $("input[name=repo_server_repo_name]").val("");
            $(".repo_server_type").attr("data-type", type);
            $(".import_menu_content").css("left", -9000);
            $(".repo_server_import_menu_content").css("left", 0);
        }
    });


    let repo_server_timeout = null;
    $("body").on("keyup", "input[name=repo_server_repo_name]", function () {
        if(importLocked) return false;

        const q = $(this).val();
        const type = $(".repo_server_type").data("type");

        clearTimeout(repo_server_timeout);
        repo_server_timeout = setTimeout(function() {
            $.ajax({
                url: "/admin/rpc/repos_search/" + type + "/" + q,
                method: "get",
                dataType: "json",
                beforeSend: function() {
                    $(".importLoader").show();
                }
            })
                .done(function(response) {
                    $(".repo_server_list tbody").html("");
                    if(response.data.length > 0) {
                        $.each(response.data, function (i, v) {
                            const ts = Date.parse(v.updated_at);
                            const date = new Date(ts);

                            let list = "<tr data-url='"+ v.clone_url +"' data-type='"+ type +"'>";
                            list += "<td>"+ v.owner.login +"</td>";
                            list += "<td>" + v.name +"</td>";
                            list += "<td>" + date.toLocaleString() +"</td>";
                            list += "</tr>";
                            $(".repo_server_list tbody").append(list);
                        });
                    } else {
                        // TODO show "nothing found" message
                    }
                })
                .always(function() {
                    $(".importLoader").hide();
                });
        }, 1000);
    });

    $("body").on("click", ".repo_server_list tr", function() {
        if(importLocked) return false;

        const repo_url = $(this).data("url");
        const repo_type = $(this).data("type");
        const projectID = $("#projectID").val();
        const eventID = $("#eID").val();
        const bookCode = $("#bookCode").val();
        const bookProject = $("#bookProject").val();
        const importLevel = $("#importLevel").val();
        const importProject = $("#importProject").val();

        $.ajax({
            url: "/admin/rpc/import",
            method: "post",
            data: {
                import: repo_url,
                type: repo_type,
                projectID: projectID,
                eventID: eventID,
                bookCode: bookCode,
                bookProject: bookProject,
                importLevel: importLevel,
                importProject: importProject
            },
            dataType: "json",
            beforeSend: function() {
                importLocked = true;
                $(".importLoader").show();
            }
        })
            .done(function(response) {
                if(response.success) {
                    renderPopup(response.message, function () {
                        location.reload();
                    }, function () {
                        location.reload();
                    });
                } else {
                    renderPopup(response.error);
                }
            })
            .always(function() {
                importLocked = false;
                $(".importLoader").hide();
            });
    });
    

    // Show event contributors
    $(".showContributors").click(function () {
        var eventID = $(this).data("eventid");
        var level = $(this).data("level");
        var mode = $(this).data("mode");

        $.ajax({
            url: "/admin/rpc/get_event_contributors",
            method: "post",
            data: {eventID: eventID, level: level, mode: mode},
            dataType: "json",
            beforeSend: function() {
                $(".showContributors").prop("disabled", true);
            }
        })
            .done(function(data) {
                if(data.success)
                {
                    var html = "";

                    // Render facilitators
                    html += "<div class='admins_list'>" +
                        "<div class='contrib_title'>"+Language.facilitators+":</div>";
                    html += "<table class='table table-bordered table-hover' role='grid'>" +
                        "<tr>"+
                            "<th>First Name</th>"+
                            "<th>Last Name</th>"+
                            "<th>UserName</th>"+
                            "<th>Role</th>"+
                            "<th>Email</th>"+
                            "<th>Sign Up Date</th>"+
                            "<th>CC by SA</th>"+
                            "<th>SoF</th>"+
                            "<th>Date Signed</th>"+
                        "</tr>";

                    $.each(data.admins, function (i,v) {
                        var formated = "---";
                        if(v.signup != "---") {
                            var ts = Date.parse(v.signup);
                            var date = new Date(ts);
                            formated = date.toLocaleDateString();
                        }

                        html += "<tr>" +
                            "<td>"+v.fname+"</td>"+
                            "<td>"+v.lname+"</td>"+
                            "<td><a href='/members/profile/"+i+"'>"+v.uname+"</a></td>"+
                            "<td>"+v.role+"</td>"+
                            "<td>"+v.email+"</td>"+
                            "<td>"+formated+"</td>"+
                            "<td>"+v.tou+"</td>"+
                            "<td>"+v.sof+"</td>"+
                            "<td>"+formated+"</td>"+
                        "</tr>";
                    });
                    html += "</table>";

                    // Render translators
                    if(Object.keys(data.translators).length > 0)
                    {
                        html += "<div class='translators_list'>" +
                            "<div class='contrib_title'>"+Language.translators+":</div>";
                        html += "<table class='table table-bordered table-hover' role='grid'>" +
                            "<tr>"+
                                "<th>First Name</th>"+
                                "<th>Last Name</th>"+
                                "<th>UserName</th>"+
                                "<th>Role</th>"+
                                "<th>Email</th>"+
                                "<th>Sign Up Date</th>"+
                                "<th>CC by SA</th>"+
                                "<th>SoF</th>"+
                                "<th>Date Signed</th>"+
                            "</tr>";
                        $.each(data.translators, function (i,v) {
                            var formated = "---";
                            if(v.signup != "---") {
                                var ts = Date.parse(v.signup);
                                var date = new Date(ts);
                                formated = date.toLocaleDateString();
                            }

                            html += "<tr>" +
                                "<td>"+v.fname+"</td>"+
                                "<td>"+v.lname+"</td>"+
                                "<td><a href='/members/profile/"+i+"'>"+v.uname+"</a></td>"+
                                "<td>"+v.role+"</td>"+
                                "<td>"+v.email+"</td>"+
                                "<td>"+formated+"</td>"+
                                "<td>"+v.tou+"</td>"+
                                "<td>"+v.sof+"</td>"+
                                "<td>"+formated+"</td>"+
                            "</tr>";
                        });
                        html += "</table>";
                    }

                    // Render checkers
                    html += "<div class='checkers_list'>" +
                        "<div class='contrib_title'>"+Language.checkers+":</div>";
                    html += "<table class='table table-bordered table-hover' role='grid'>" +
                        "<tr>"+
                            "<th>First Name</th>"+
                            "<th>Last Name</th>"+
                            "<th>UserName</th>"+
                            "<th>Role</th>"+
                            "<th>Email</th>"+
                            "<th>Sign Up Date</th>"+
                            "<th>CC by SA</th>"+
                            "<th>SoF</th>"+
                            "<th>Date Signed</th>"+
                        "</tr>";
                    $.each(data.checkers, function (i,v) {
                        var formated = "---";
                        if(v.signup != "---") {
                            var ts = Date.parse(v.signup);
                            var date = new Date(ts);
                            formated = date.toLocaleDateString();
                        }

                        html += "<tr>" +
                            "<td>"+v.fname+"</td>"+
                            "<td>"+v.lname+"</td>"+
                            "<td><a href='/members/profile/"+i+"'>"+v.uname+"</a></td>"+
                            "<td>"+v.role+"</td>"+
                            "<td>"+v.email+"</td>"+
                            "<td>"+formated+"</td>"+
                            "<td>"+v.tou+"</td>"+
                            "<td>"+v.sof+"</td>"+
                            "<td>"+formated+"</td>"+
                        "</tr>";
                    });
                    html += "</table>";

                    $(".contributors_content").html(html);
                    $(".contributors_container").css("left", 0);
                }
                else
                {
                    if(typeof data.error != "undefined")
                    {
                        if(data.error == "login" || data.error == "admin")
                            window.location.href = "/members/login";
                        else
                        {
                            renderPopup(data.error);
                        }
                    }
                }
            })
            .always(function() {
                $(".showContributors").prop("disabled", false);
            });
    });

    $(".contributors-close").click(function () {
        $(".contributors_container").css("left", -9999);
    });


    // Show project contributors
    $(".showAllContibutors").click(function () {
        var projectID = $(this).data("projectid");

        $.ajax({
            url: "/admin/rpc/get_project_contributors",
            method: "post",
            data: {projectID: projectID},
            dataType: "json",
            beforeSend: function() {
                $(".contibLoader").show();
            }
        })
            .done(function(data) {
                if(data.success)
                {
                    var html = "<table class='table table-bordered table-hover' role='grid'>"+
                        "<tr>"+
                            "<th>First Name</th>"+
                            "<th>Last Name</th>"+
                            "<th>UserName</th>"+
                            "<th>Role</th>"+
                            "<th>Email</th>"+
                            "<th>Sign Up Date</th>"+
                            "<th>CC by SA</th>"+
                            "<th>SoF</th>"+
                            "<th>Date Signed</th>"+
                        "</tr>";

                    $.each(data.contributors, function () {
                        var formated = "---";
                        if(this.signup != "---") {
                            var ts = Date.parse(this.signup);
                            var date = new Date(ts);
                            formated = date.toLocaleDateString();
                        }

                        html += "<tr>"+
                                    "<td>"+this.fname+"</td>"+
                                    "<td>"+this.lname+"</td>"+
                                    "<td>"+this.uname+"</td>"+
                                    "<td>"+this.role+"</td>"+
                                    "<td>"+this.email+"</td>"+
                                    "<td>"+formated+"</td>"+
                                    "<td>"+this.tou+"</td>"+
                                    "<td>"+this.sof+"</td>"+
                                    "<td>"+formated+"</td>"+
                                "</tr>";
                    });

                    html += "</table>";

                    $(".contributors_title").hide();
                    $(".contributors_title.proj").show();
                    $(".contributors_content").html(html);
                    $(".contributors_container").css("left", 0);
                }
                else
                {
                    if(typeof data.error != "undefined")
                    {
                        if(data.error == "login" || data.error == "admin")
                            window.location.href = "/members/login";
                        else
                        {
                            renderPopup(data.error);
                        }
                    }
                }
            })
            .always(function() {
                $(".contibLoader").hide();
            });
    });

    $(".contributors-close").click(function () {
        $(".contributors_container").css("left", -9999);
        $(".contributors_title").show();
        $(".contributors_title.proj").hide();
    });


    // Activate/Verify member
    $(".verifyMember").click(function (e) {
        e.preventDefault();

        var memberID = $(this).attr("data");
        var parent = $(this).parents("tr");
        var activated = $(".activateMember", parent).is(":checked");

        var msg = "";
        if(!activated)
            msg += Language.notActivatedWarning + " ";
        msg += Language.verifyMessage;

        renderConfirmPopup(Language.verifyTitle, msg, function () {
            $(this).dialog("close");

            $.ajax({
                url: "/admin/rpc/verify_member",
                method: "post",
                data: {
                    memberID: memberID,
                },
                dataType: "json",
                beforeSend: function() {
                    //$(".commentEditorLoader").show();
                }
            })
                .done(function(data) {
                    if(data.success)
                    {
                        // renderPopup(Language.verifySuccess);
                        parent.remove();
                    }
                    else
                    {
                        if(typeof data.error != "undefined")
                        {
                            renderPopup(data.error, function () {
                                window.location.reload(true);
                            });
                        }
                    }
                })
                .always(function() {
                    //$(".commentEditorLoader").hide();
                });
        });

        return false;
    });


    // Block/Unblock member
    $("body").on("click", ".blockMember", function (e) {
        e.preventDefault();

        var $this = $(this);
        var memberID = $this.attr("data");

        $.ajax({
            url: "/admin/rpc/block_member",
            method: "post",
            data: {
                memberID: memberID,
            },
            dataType: "json",
            beforeSend: function() {
                //$(".commentEditorLoader").show();
            }
        })
            .done(function(data) {
                if(data.success)
                {
                    if(data.blocked)
                    {
                        renderPopup(Language.blockedSuccess);
                        $this.removeClass("btn-danger")
                            .addClass("btn-primary")
                            .text(Language.unblock);
                    }
                    else
                    {
                        renderPopup(Language.unblockedSuccess);
                        $this.removeClass("btn-primary")
                            .addClass("btn-danger")
                            .text(Language.block);
                    }
                }
                else
                {
                    if(typeof data.error != "undefined")
                    {
                        renderPopup(data.error, function () {
                            window.location.reload(true);
                        });
                    }
                }
            })
            .always(function() {
                //$(".commentEditorLoader").hide();
            });
    });

    // Members tabs switch
    $(".mems_tab").click(function () {
        var id = $(this).attr("id");

        $(".members_content").removeClass("shown");
        $(".mems_tab").removeClass("active");

        $(this).addClass("active");
        $("#"+id+"_content").addClass("shown");

        return false;
    });

    if ($("select.mems_language").length > 0) {
        $("select.mems_language").chosen();
    }

    // Submit Filter form
    $(".filter_apply button").click(function () {
        var button = $(this);
        button.prop("disabled", true);
        $(".filter_page").val(1);

        $.ajax({
            url: "/admin/rpc/search_members",
            method: "post",
            data: $("#membersFilter").serialize(),
            dataType: "json",
            beforeSend: function() {
                $(".filter_loader").show();
            }
        })
            .done(function(data) {
                if(data.success)
                {
                    if(data.members.length > 0)
                    {
                        $("#all_members_table").show();
                        $(".filter_page").val(1);

                        // if it has more results to show draw "more" button
                        if(data.members.length < parseInt(data.count))
                        {
                            if($("#search_more").length <= 0)
                            {
                                $('<div id="search_more"></div>').appendTo("#all_members_content")
                                    .text(Language.searchMore);
                            }
                            $(".filter_page").val(2);
                        }
                        else
                        {
                            $("#search_more").remove();
                        }

                        $("#search_empty").remove();
                    }
                    else
                    {
                        $("#all_members_table").hide();
                        if($("#search_empty").length <= 0)
                            $('<div id="search_empty"></div>').appendTo("#all_members_content")
                                .text(Language.searchEmpty);
                        $('#search_more').remove();
                    }

                    $("#all_members_table tbody").html("");
                    $.each(data.members, function (i, v) {
                        var row = "<tr>" +
                            "<td><a href='/members/profile/"+v.memberID+"'>"+v.userName+"</a></td>" +
                            "<td>"+v.firstName+" "+v.lastName+"</td>" +
                            "<td>"+v.email+"</td>" +
                            "<td>"+(v.projects ? JSON.parse(v.projects).map(function (proj) {
                                return Language[proj];
                            }).join(", ") : "")+"</td>" +
                            "<td>"+(v.proj_lang ? "["+v.langID+"] "+v.langName +
                                (v.angName != "" && v.angName != v.langName ? " ("+v.angName+")" : "") : "")+"</td>" +
                            "<td><input type='checkbox' "+(parseInt(v.complete) ? "checked" : "")+" disabled></td>" +
                            "<td class=\"block_btn\"><button class='blockMember btn "+(v.blocked == 1 ? "btn-primary" : "btn-danger")+"' data='"+v.memberID+"'>" +
                                (v.blocked == 1 ? Language.unblock : Language.block)+"</button></td>" +
                            "</tr>";
                        $("#all_members_table tbody").append(row);
                    });
                }
                else
                {
                    if(typeof data.error != "undefined")
                    {
                        renderPopup(data.error);
                    }
                }
            })
            .always(function() {
                $(".filter_loader").hide();
                button.prop("disabled", false);
            });

        return false;
    });

    $("body").on("click", "#search_more", function () {
        var button = $(this);

        if(button.hasClass("disabled")) return false;

        button.addClass("disabled");
        var page = parseInt($(".filter_page").val());

        $.ajax({
            url: "/admin/rpc/search_members",
            method: "post",
            data: $("#membersFilter").serialize(),
            dataType: "json",
            beforeSend: function() {
                $(".filter_loader").show();
            }
        })
            .done(function(data) {
                if(data.success)
                {
                    if(data.members.length > 0)
                    {
                        $(".filter_page").val(page+1);
                        $.each(data.members, function (i, v) {
                            var row = "<tr>" +
                                "<td><a href='/members/profile/"+v.memberID+"'>"+v.userName+"</a></td>" +
                                "<td>"+v.firstName+" "+v.lastName+"</td>" +
                                "<td>"+v.email+"</td>" +
                                "<td>"+(v.projects ? JSON.parse(v.projects).map(function (proj) {
                                    return Language[proj];
                                }).join(", ") : "")+"</td>" +
                                "<td>"+(v.proj_lang ? "["+v.langID+"] "+v.langName +
                                    (v.angName != "" && v.angName != v.langName ? " ("+v.angName+")" : "") : "")+"</td>" +
                                "<td><input type='checkbox' "+(parseInt(v.complete) ? "checked" : "")+" disabled></td>" +
                                "<td class=\"block_btn\"><button class='blockMember btn "+(v.blocked == 1 ? "btn-primary" : "btn-danger")+"' data='"+v.memberID+"'>" +
                                (v.blocked == 1 ? Language.unblock : Language.block)+"</button></td>" +
                                "</tr>";
                            $("#all_members_table tbody").append(row);
                        });

                        var results = parseInt($("#all_members_table tbody tr").length);
                        if(results >= parseInt(data.count))
                            $('#search_more').remove();
                    }
                    else
                    {
                        $('#search_more').remove();
                    }
                }
                else
                {
                    if(typeof data.error != "undefined")
                    {
                        renderPopup(data.error);
                    }
                }
            })
            .always(function() {
                $(".filter_loader").hide();
                button.removeClass("disabled");
            });
    });

    // Clear members filter
    $(".filter_clear").click(function () {
        $("#membersFilter")[0].reset();
        $(".mems_language").val('').trigger("chosen:updated");
        return false;
    });

    // Admin tools

    // Update languages database
    $(".update_langs button").click(function () {
        const downloadAgain = $(".update_langs .download_again").is(":checked");
        const url = $(".update_langs input[name=url]").val();
        $.ajax({
            url: "/admin/rpc/update_languages",
            method: "post",
            data: { download: downloadAgain ? 1 : 0, url },
            dataType: "json",
            beforeSend: function() {
                $(".update_langs img").show();
            }
        })
            .done(function(data) {
                if(data.success) {
                    renderPopup("Updated!");
                } else {
                    if(typeof data.error != "undefined") {
                        $(".update_langs input[name=url]").val(data.oldUrl);
                        renderPopup(data.error);
                    }
                }
            })
            .always(function() {
                $(".update_langs img").hide();
            });
    });

    // Update source catalog
    $(".update_catalog button").click(function () {
        const downloadAgain = $(".update_catalog .download_again").is(":checked");
        //const url = $(".update_catalog input[name=url]").val();
        $.ajax({
            url: "/admin/rpc/update_catalog",
            method: "post",
            data: { download: downloadAgain ? 1 : 0, /*url*/ },
            dataType: "json",
            beforeSend: function() {
                $(".update_catalog img").show();
            }
        })
            .done(function(data) {
                if(data.success) {
                    renderPopup("Updated!");
                } else {
                    if(typeof data.error !== "undefined") {
                        renderPopup(data.error);
                    }
                }
            })
            .always(function() {
                $(".update_catalog img").hide();
            });
    });

    // Clear all cache
    $(".clear_cache button").click(function () {
        $.ajax({
            url: "/admin/rpc/clear_all_cache",
            method: "post",
            dataType: "json",
            beforeSend: function() {
                $(".clear_cache img").show();
            }
        })
            .done(function(data) {
                if(data.success)
                {
                    renderPopup("Entire cache has been cleared!");
                }
                else
                {
                    if(typeof data.error != "undefined")
                    {
                        renderPopup(data.error);
                    }
                }
            })
            .always(function() {
                $(".clear_cache img").hide();
            });
    });

    // Create multiple users
    $(".create_users button").click(function () {
        var amount = $(".create_users #amount").val();
        var langs = $(".create_users #langs").val();
        var password = $(".create_users #password").val();

        $.ajax({
            url: "/admin/rpc/create_multiple_users",
            method: "post",
            data: {amount: amount, langs: langs, password: password},
            dataType: "json",
            beforeSend: function() {
                $(".create_users img").show();
            }
        })
            .done(function(data) {
                if(data.success)
                {
                    $(".create_users #amount").val("");
                    $(".create_users #langs").val("");
                    $(".create_users #password").val("");

                    renderPopup(data.msg);
                }
                else
                {
                    if(typeof data.error != "undefined")
                    {
                        renderPopup(data.error);
                    }
                }
            })
            .always(function() {
                $(".create_users img").hide();
            });
    });


    // Managing SAIL Dictionary

    // Delete word
    $("body").on("click", ".tools_delete_word", function (e) {
        var li = $(this).parent("li");
        var word = li.attr("id");

        renderConfirmPopup(Language.attention, Language.delSailword + word + "?", function () {
            $( this ).dialog( "close" );
            $.ajax({
                url: "/admin/rpc/delete_sail_word",
                method: "post",
                data: {word: word},
                dataType: "json",
                beforeSend: function() {
                    $("img", li).show();
                }
            })
                .done(function(data) {
                    if(data.success)
                    {
                        li.remove();
                    }
                    else
                    {
                        if(typeof data.error != "undefined")
                        {
                            renderPopup(data.error);
                        }
                    }
                })
                .always(function() {
                    $("img", li).hide();
                });
        });

        e.preventDefault();
        return false;
    });

    // Create word
    $("body").on("click", ".sail_create .add_word", function (e) {
        var word = $("#sailword").val();
        var symbol = $("#sailsymbol").val();

        $.ajax({
            url: "/admin/rpc/create_sail_word",
            method: "post",
            data: {word: word, symbol: symbol},
            dataType: "json",
            beforeSend: function() {
                $("#sail_create_loader").show();
            }
        })
            .done(function(data) {
                if(data.success)
                {
                    $("#sailword").val("");
                    $("#sailsymbol").val("");
                    $(".sail_list.tools ul").append(data.li);
                }
                else
                {
                    if(typeof data.error != "undefined")
                    {
                        renderPopup(data.error);
                    }
                }
            })
            .always(function() {
                $("#sail_create_loader").hide();
            });

        e.preventDefault();
        return false;
    });


    // Manage FAQ

    // Delete question
    $("body").on("click", ".tools_delete_faq", function (e) {
        var li = $(this).parent("li");
        var questionID = li.attr("id");

        renderConfirmPopup(Language.attention, Language.delQuestion, function () {
            $( this ).dialog( "close" );
            $.ajax({
                url: "/admin/rpc/delete_faq",
                method: "post",
                data: {id: questionID},
                dataType: "json",
                beforeSend: function() {
                    $("img", li).show();
                }
            })
                .done(function(data) {
                    if(data.success)
                    {
                        li.remove();
                    }
                    else
                    {
                        if(typeof data.error != "undefined")
                        {
                            renderPopup(data.error);
                        }
                    }
                })
                .always(function() {
                    $("img", li).hide();
                });
        });

        e.preventDefault();
        return false;
    });

    // Create Question
    $("body").on("click", ".faq_create .create_faq", function (e) {
        var question = $("#faq_question").val();
        var answer = $("#faq_answer").val();
        var category = $("#faq_category").val();

        $.ajax({
            url: "/admin/rpc/create_faq",
            method: "post",
            data: {
                question: question,
                answer: answer,
                category: category
            },
            dataType: "json",
            beforeSend: function() {
                $("#faq_create_loader").show();
            }
        })
            .done(function(data) {
                if(data.success)
                {
                    $("#faq_question").val("");
                    $("#faq_answer").val("");
                    $("#faq_category").val("");
                    $(".faq_list.tools ul").prepend(data.li);
                    $('#faq_answer').summernote('reset');
                }
                else
                {
                    if(typeof data.error != "undefined")
                    {
                        renderPopup(data.error);
                    }
                }
            })
            .always(function() {
                $("#faq_create_loader").hide();
            });

        e.preventDefault();
        return false;
    });


    // Create News
    $(".create_news button").click(function (e) {
        var title = $("#title").val();
        var category = $("#category").val();
        var text = $("#text").val();

        $.ajax({
            url: "/admin/rpc/create_news",
            method: "post",
            data: {title: title, category: category, text: text},
            dataType: "json",
            beforeSend: function() {
                $(".create_news img").show();
            }
        })
            .done(function(data) {
                if(data.success)
                {
                    $("#title").val("");
                    $("#category").val("");
                    $("#text").val("");

                    renderPopup(data.msg);
                }
                else
                {
                    if(typeof data.error != "undefined")
                    {
                        renderPopup(data.error);
                    }
                }
            })
            .always(function() {
                $(".create_news img").hide();
            });

        e.preventDefault();
        return false;
    });

    // Upload SUN font
    $(".sun_font_tools button").click(function (e) {
        var formData = new FormData();
        formData.append("file", $('#sun_upload')[0].files[0]);

        $.ajax({
            url: "/admin/rpc/upload_sun_font",
            method: "post",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            beforeSend: function() {
                $(".sun_font_tools img").show();
            }
        })
            .done(function(data) {
                if(data.success)
                {
                    renderPopup(data.message);
                    $('#sun_upload').val("");
                }
                else
                {
                    if(typeof data.error != "undefined")
                    {
                        renderPopup(data.error);
                    }
                }
            })
            .always(function() {
                $(".sun_font_tools img").hide();
            });

        e.preventDefault();
        return false;
    });

    // Upload SUN dictionary (.csv)
    $(".saildict_upload button").click(function (e) {
        var formData = new FormData();
        formData.append("file", $('#saildic_upload')[0].files[0]);

        $.ajax({
            url: "/admin/rpc/upload_sun_dict",
            method: "post",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            beforeSend: function() {
                $(".saildict_upload img").show();
            }
        })
            .done(function(data) {
                if(data.success)
                {
                    renderPopup(data.message);
                    $('#saildic_upload').val("");
                }
                else
                {
                    if(typeof data.error != "undefined")
                    {
                        renderPopup(data.error);
                    }
                }
            })
            .always(function() {
                $(".saildict_upload img").hide();
            });

        e.preventDefault();
        return false;
    });

    $(".event_column.progress").each(function () {
        var $this = $(this);
        var eventID = $this.data("eventid");

        if(typeof eventID == "string" || eventID == "") return;

        $.ajax({
            url: "/admin/rpc/get_event_progress/" + eventID,
            method: "get",
            dataType: "json",
            beforeSend: function() {
                $(".progressLoader", $this).show();
            }
        })
            .done(function(data) {
                if(data.success)
                {
                    if(data.progress > 0) {
                        $this.removeClass("zero");
                        $(".progress-bar", $this).attr("aria-valuenow", data.progress);
                        $(".progress-bar", $this).css("width", data.progress + "%");
                        $(".progress-bar", $this).text(Math.floor(data.progress) + "%");
                    }
                }
                else
                {
                    console.log("unavailable");
                }
            })
            .always(function() {
                $(".progressLoader", $this).hide();
            });
    });

    // Upload image for FAQ
    $('#faq_answer').summernote({
        dialogsInBody: true,
        height: 300,
        minHeight: null,
        maxHeight: null,
        shortCuts: false,
        disableDragAndDrop: false,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['Insert', ['video', 'picture', 'link', 'table', 'hr']],
            ['Other', ['codeview', 'undo', 'redo', 'fullscreen']]
        ],
        callbacks: {
            onImageUpload: function(image) {
                uploadImageContent(image[0], $(this));
            }
        }
    });

    $(".members_download_tsv").on("click", function() {
        var tsv = exportTableToSV($("#all_books_content")[0], "\t");
        downloadSV(tsv, "report.tsv", "tsv");
    });

    $(".contribs_download_tsv").on("click", function() {
        var tsv = exportTableToSV($(".contributors_content")[0], "\t");
        downloadSV(tsv, "contributors.tsv", "tsv");
    });

    $(".add_custom_src").click(function () {
        $(".custom_src_type").toggle(200);
    });

    $(".custom_src_type button").on("click", function () {
        var srcSlug = $(".custom_src_type #src_slug").val();
        var srcName = $(".custom_src_type #src_name").val();

        if(srcSlug.trim() != "" && srcName.trim() != "") {
            if(!/\|/g.test(srcSlug) && !/\|/g.test(srcName)) {
                var newOption = '<option value="'+srcSlug+'|'+srcName+'" selected>' +
                    '['+srcSlug+'] ' + srcName +
                    '</option>';
                $("#src_type").append(newOption);
                $("#src_type").val(srcSlug + "|" + srcName).trigger("chosen:updated");

                $(".custom_src_type #src_slug").val("");
                $(".custom_src_type #src_name").val("")
                $(".custom_src_type").slideUp(200);
            } else {
                renderPopup("Please don't use '|' character");
            }
        }
    });

    $(".src_create").click(function () {
        var srcLang = $("#src_language").val();
        var srcType = $("#src_type").val();

        if(srcLang.trim() !== "" && srcType.trim() !== "") {
            $.ajax({
                url: "/admin/rpc/create_custom_src",
                method: "post",
                dataType: "json",
                data: {
                    lang: srcLang,
                    type: srcType
                },
                beforeSend: function() {
                    $(".src_loader").show();
                }
            })
                .done(function(data) {
                    if(data.success)
                    {
                        if(typeof data.message != "undefined") {
                            renderPopup(data.message, function () {
                                location.reload();
                            });
                        } else {
                            location.reload();
                        }
                    }
                    else
                    {
                        if(typeof data.error != "undefined") {
                            renderPopup(data.error);
                        }
                    }
                })
                .always(function() {
                    $(".src_loader").hide();
                });
        } else {
            renderPopup("Wrong parameters");
        }
    });

    // Upload source
    $("button.src_upload").click(function (e) {
        var formData = new FormData();
        formData.append("file", $('.source_upload #src_upload')[0].files[0]);
        formData.append("src", $(".source_upload #src").val());

        $.ajax({
            url: "/admin/rpc/upload_source",
            method: "post",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            beforeSend: function() {
                $(".source_upload img").show();
            }
        })
            .done(function(data) {
                if(data.success)
                {
                    renderPopup(data.message);
                    $("#src").val("").trigger("chosen:updated");
                    $('#src_upload').val("");
                }
                else
                {
                    if(typeof data.error != "undefined")
                    {
                        renderPopup(data.error);
                    }
                }
            })
            .always(function() {
                $(".source_upload img").hide();
            });

        e.preventDefault();
        return false;
    });

    // Upload source
    $(".src_update").click(function () {
        var src = $(".source_update #src").val();

        if(src.trim() !== "") {
            $.ajax({
                url: "/admin/rpc/update_source",
                method: "post",
                dataType: "json",
                data: {
                    src: src,
                },
                beforeSend: function() {
                    $(".source_update img").show();
                }
            })
                .done(function(data) {
                    if(data.success)
                    {
                        renderPopup(data.message);
                        $(".source_update #src").val("").trigger("chosen:updated");
                    }
                    else
                    {
                        if(typeof data.error != "undefined") {
                            renderPopup(data.error);
                        }
                    }
                })
                .always(function() {
                    $(".source_update img").hide();
                });
        } else {
            renderPopup("Wrong parameters");
        }
    });
});


// --------------- Variables ---------------- //
var EventStates = {
    states: {
        "started": 0,
        "translating": 1,
        "translated": 2,
        "l2_recruit": 3,
        "l2_check": 4,
        "l2_checked": 5,
        "l3_recruit": 6,
        "l3_check": 7,
        "complete": 8
    }
};

var ImportStates = {
    DEFAULT: 0,
    PROGRESS: 1,
    DONE: 2
};


// --------------- Functions ---------------- //
function setImportLinks(project, importState)
{
    let done = $("."+project+"_import .import_done");
    let progress = $("."+project+"_import .import_progress");

    switch (importState) {
        case ImportStates.PROGRESS:
            done.removeClass("done").hide();
            progress.show();
            break;
        case ImportStates.DONE:
            done.addClass("done").show();
            progress.hide();
            break;
        default:
            done.removeClass("done").show();
            progress.hide();
    }
}

function setImportLinksUlb(data) {
    if(typeof data.ulb != "undefined")
    {
        switch (EventStates.states[data.ulb.state]) {
            case EventStates.states.l2_recruit:
            case EventStates.states.l2_check:
                setImportLinks("l2", ImportStates.PROGRESS);
                break;
            case EventStates.states.l2_checked:
                setImportLinks("l2", ImportStates.DONE);
                break;

            case EventStates.states.l3_recruit:
            case EventStates.states.l3_check:
                setImportLinks("l2", ImportStates.DONE);
                setImportLinks("l3", ImportStates.PROGRESS);
                break;
            case EventStates.states.complete:
                setImportLinks("l2", ImportStates.DONE);
                setImportLinks("l3", ImportStates.DONE);
                break;
        }
    }

    switch (EventStates.states[data.event.state]) {
        case EventStates.states.translated:
        case EventStates.states.l3_check:
        case EventStates.states.l3_recruit:
            if(["tn","tq","tw"].indexOf(data.event.project.bookProject) > -1)
            {
                $(".import.l2_import").show();
                $(".import.l3_import").show();
            }
            break;
    }
}

function setImportComponent(event) {
    switch (EventStates.states[event.state]) {
        case EventStates.states.started:
        case EventStates.states.translating:
            if(["ulb","udb","sun"].indexOf(event.project.bookProject) > -1)
            {
                $(".event_l_1").prop("checked", true);
                setImportLinks("l1", ImportStates.PROGRESS);
                $(".input_mode_group").slideDown();
            }
            else
            {
                $(".event_l_2").prop("checked", true);
                setImportLinks(event.project.bookProject, ImportStates.PROGRESS);
                setImportLinks(event.project.bookProject+"_l1", ImportStates.PROGRESS);
                setImportLinks(event.project.bookProject+"_l2", ImportStates.PROGRESS);
            }
            break;
        case EventStates.states.translated:
            $(".event_l_1").prop("disabled", true);

            if(["ulb","udb","sun"].indexOf(event.project.bookProject) > -1)
            {
                $(".event_l_2").prop("checked", true);
                setImportLinks("l1", ImportStates.DONE);
                $(".l2_import").hide();
                $(".revision_mode").prop("disabled", false);
                $(".revision_mode_group").slideDown();
            }
            else
            {
                $(".event_l_2").prop("disabled", true);
                $(".event_l_3").prop("checked", true);
                setImportLinks(event.project.bookProject, ImportStates.DONE);
                setImportLinks(event.project.bookProject+"_l1", ImportStates.DONE);
                setImportLinks(event.project.bookProject+"_l2", ImportStates.DONE);
                $("."+event.project.bookProject+"_l1_import").hide();
                $("."+event.project.bookProject+"_l2_import").show();
            }
            $(".event_imports").show();
            break;

        case EventStates.states.l2_recruit:
        case EventStates.states.l2_check:
            $(".event_l_1").prop("disabled", true);
            $(".event_l_2").prop("checked", true);
            setImportLinks("l1", ImportStates.DONE);
            setImportLinks("l2", ImportStates.PROGRESS);
            $(".l1_import").show();
            $(".l2_import").hide();
            $(".event_imports").show();
            $(".revision_mode_group").slideDown();
            break;
        case EventStates.states.l2_checked:
            // Notes, Questions and Words don't run through these states
            $(".event_l_1").prop("disabled", true);
            $(".event_l_2").prop("disabled", true);
            $(".event_l_3").prop("checked", true);

            $(".l1_import").hide();
            $(".l2_import").show();
            setImportLinks("l1", ImportStates.DONE);
            setImportLinks("l2", ImportStates.DONE);

            $(".event_imports").show();
            break;

        case EventStates.states.l3_recruit:
        case EventStates.states.l3_check:
        case EventStates.states.complete:
            $(".event_l_1").prop("disabled", true);
            $(".event_l_2").prop("disabled", true);
            $(".event_l_3").prop("checked", true);

            if(["ulb","udb","sun"].indexOf(event.project.bookProject) > -1)
            {
                $(".l1_import").hide();
                $(".l2_import").show();
                setImportLinks("l2", ImportStates.DONE);
            }
            else
            {
                setImportLinks(event.project.bookProject, ImportStates.DONE);
                setImportLinks(event.project.bookProject+"_l1", ImportStates.DONE);
                setImportLinks(event.project.bookProject+"_l2", ImportStates.DONE);
                $("."+event.project.bookProject+"_l1_import").hide();
                $("."+event.project.bookProject+"_l2_import").show();
            }
            $(".event_imports").show();
            break;
    }
}

function setEventMenuLinks(event, level) {
    $("#initialLevel").val(level);
    const mode = event.project.bookProject;
    const category = event.book_info.category;

    switch (mode) {
        case "ulb":
        case "udb":
        case "sun":
            if (category === "odb") {
                $(".event_links_l1").hide();
                $(".event_links_l2").hide();
                $(".event_links_l3").show();

                $(".event_links_l3 .event_progress a")
                    .attr("href", "/events/information-odb-sun/"+event.eventID);
                $(".event_links_l3 .event_manage a")
                    .attr("href", "/events/manage/"+event.eventID);
            } else {
                $(".event_links_l1").show();
                $(".event_links_l1 .event_progress a")
                    .attr(
                        "href",
                        "/events/information" + (mode === "sun" ? "-sun" : "") + "/"+event.eventID
                    );
                $(".event_links_l1 .event_manage a").attr("href", "/events/manage/"+event.eventID);
                $(".event_links_l2").hide();
                $(".event_links_l3").hide();

                switch (level) {
                    case 2:
                        $(".event_links_l2").show();
                        $(".event_links_l2 .event_progress a")
                            .attr(
                                "href",
                                "/events/information" + (mode === "sun" ? "-sun" : "") + "-revision/" + event.eventID
                            );
                        $(".event_links_l2 .event_manage a")
                            .attr("href", "/events/manage-revision/" + event.eventID);
                        break;
                    case 3:
                        $(".event_links_l3").show();
                        $(".event_links_l3 .event_progress a")
                            .attr(
                                "href",
                                "/events/information" + (mode === "sun" ? "-sun" : "") + "-review/" + event.eventID
                            );
                        $(".event_links_l3 .event_manage a")
                            .attr("href", "/events/manage-review/" + event.eventID);
                        break;
                }
            }
            break;
        case "tn":
        case "tq":
        case "tw":
        case "obs":
        case "bc":
        case "bca":
            $(".event_links_l1").hide();
            $(".event_links_l2").show();
            $(".event_links_l2 .event_progress a")
                .attr("href", "/events/information-" + event.project.bookProject + "/"+event.eventID);
            $(".event_links_l2 .event_manage a")
                .attr("href", "/events/manage/"+event.eventID);
            $(".event_links_l3").hide();

            if(level == 3)
            {
                $(".event_links_l3").show();
                $(".event_links_l3 .event_progress a")
                    .attr("href", "/events/information-" + event.project.bookProject + "-review/"+event.eventID);
                $(".event_links_l3 .event_manage a")
                    .attr("href", "/events/manage-review/"+event.eventID);
            }

            if(["tw","bca"].indexOf(event.project.bookProject) > -1)
            {
                const projAdd = event.project.bookProject === "tw" ? "tw" : "words";
                $(".event_links_l2 .event_manage a")
                    .attr("href", "/events/manage-"+projAdd+"/"+event.eventID);

                if(level == 3)
                    $(".event_links_l3 .event_manage a")
                        .attr("href", "/events/manage-"+projAdd+"-review/"+event.eventID);
            }
            break;
        case "rad":
            $(".event_links_l1").hide();
            $(".event_links_l2").hide();
            $(".event_links_l3").show();

            $(".event_links_l3 .event_progress a")
                .attr("href", "/events/information-rad/"+event.eventID);
            $(".event_links_l3 .event_manage a")
                .attr("href", "/events/manage/"+event.eventID);
            break;
    }
}


function setEventMenu(event) {
    switch (EventStates.states[event.state]) {
        case EventStates.states.started:
        case EventStates.states.translating:
        case EventStates.states.translated:
            if(["ulb","udb","sun"].indexOf(event.project.bookProject) > -1)
                setEventMenuLinks(event, 1);
            else if(["tn","tq","tw","obs","bc","bca"].indexOf(event.project.bookProject) > -1)
                setEventMenuLinks(event, 2);
            else
                setEventMenuLinks(event, 3);
            break;

        case EventStates.states.l2_recruit:
        case EventStates.states.l2_check:
        case EventStates.states.l2_checked:
            setEventMenuLinks(event, 2);
            break;

        case EventStates.states.l3_recruit:
        case EventStates.states.l3_check:
        case EventStates.states.complete:
            setEventMenuLinks(event, 3);
            break;
    }
}

function setStartEventButton(event) {
    switch (EventStates.states[event.state]) {
        case EventStates.states.translated:
        case EventStates.states.l2_checked:
            $("button[name=startEvent]").text(Language.create);
            $("#eventAction").val("create");
            break;
        default:
            $("button[name=startEvent]").text(Language.save);
            $("#eventAction").val("edit");
    }
}

function resetProjectForm() {
    $("#project").trigger("reset");
    $("#projectID").val("");
    $("#project_admins").val("").trigger("chosen:updated");
    $("#projectMode").prop("disabled", false).trigger("chosen:updated");
    $("#subGwLangs").prop("disabled", false).trigger("chosen:updated");
    $("#targetLangs").prop("disabled", false).trigger("chosen:updated");
    $("#projectType").prop("disabled", false).trigger("chosen:updated");
    $("#sourceTranslation").val("").trigger("chosen:updated");
    $(".sourceTools").addClass("hidden");
    $(".sourceTranslation").removeClass("hidden");
    $(".projectType").removeClass("hidden");
    $(".subErrors").html("");
    $("#project select").val('').chosen();
    $("button[name=project]").text(Language.create);
    $("#projectAction").val("create");
    $(".toolsTn").removeClass("hidden");
    $("#toolsTn").val("").trigger("chosen:updated");
    $(".toolsTq").removeClass("hidden");
    $("#toolsTq").val("").trigger("chosen:updated");
    $(".toolsTw").removeClass("hidden");
    $("#toolsTw").val("").trigger("chosen:updated");
    $(".toolsBc").removeClass("hidden");
    $("#toolsBc").val("").trigger("chosen:updated");
}

function setProjectForm(data) {
    $("#projectID").val(data.project.projectID);

    var admins = data.project.admins;
    var content = "";
    $.each(admins, function(k, v) {
        var name = v.firstName + " " + v.lastName.charAt(0) + ". ("+v.userName+")";
        content += '' +
            '<option value="' + v.memberID + '" selected>' + name + '</option>';
    });

    $("#project_admins").prepend(content);

    let mode;
    if(["ulb","udb","sun"].indexOf(data.project.bookProject) > -1) {
        if(data.project.sourceBible === "odb") {
            mode = "odb";
        } else {
            mode = "bible";
        }
    } else {
        mode = data.project.bookProject;
    }

    $("#projectMode")
        .val(mode)
        .trigger("chosen:updated")
        .trigger("change")
        .prop("disabled", true);

    $("#subGwLangs")
        .val(data.project.gwLang + "|" + data.project.glID)
        .trigger("chosen:updated")
        .trigger("change")
        .prop("disabled", true);

    $("#targetLangs").prop("disabled", true);
    document.addEventListener("target-langs-updated", function() {
        $("#targetLangs")
            .val(data.project.targetLang)
            .trigger("chosen:updated");
    });

    $("#sourceTranslation")
        .val(data.project.sourceBible + "|" + data.project.sourceLangID)
        .trigger("chosen:updated");

    if(["tn","tq","tw","bc","bca"].indexOf(data.project.bookProject) > -1)
    {
        $("#sourceTools").val(data.project.resLangID).trigger("chosen:updated");
        $(".toolsTn").addClass("hidden");
        $(".toolsTq").addClass("hidden");
        $(".toolsTw").addClass("hidden");
        $(".toolsBc").addClass("hidden");
    } else {
        $("#projectType")
            .val(data.project.bookProject)
            .trigger("chosen:updated")
            .prop("disabled", true);
        $("#toolsTn").val(data.project.tnLangID).trigger("chosen:updated");
        $("#toolsTq").val(data.project.tqLangID).trigger("chosen:updated");
        $("#toolsTw").val(data.project.twLangID).trigger("chosen:updated");
        $("#toolsBc").val(data.project.bcLangID).trigger("chosen:updated");
    }
}

function resetEventForm() {
    $("#startEvent").trigger("reset");

    $("#eID").val(0);
    $("#initialLevel").val(1);
    $(".main_menu").hide();
    $("#adminsSelect").empty().trigger("chosen:updated");
    $(".delinput").hide();
    $(".errors").html("");
    $(".input_mode_group").hide();
    $(".revision_mode_group").hide();
    $(".input_mode").prop("disabled", false);
    $(".revision_mode").prop("disabled", false);

    $(".event_links_l1").show();
    $(".event_links_l1 a").attr("href", "#");
    $(".event_links_l2").show();
    $(".event_links_l2 a").attr("href", "#");
    $(".event_links_l3").show();
    $(".event_links_l3 a").attr("href", "#");

    $(".event_imports").hide();
    $("input[name=eventLevel]").prop("disabled", false);

    setImportLinks("l1", ImportStates.DEFAULT);
    setImportLinks("l2", ImportStates.DEFAULT);
    setImportLinks("l3", ImportStates.DEFAULT);
    setImportLinks("tn_l1", ImportStates.DEFAULT);
    setImportLinks("tn_l2", ImportStates.DEFAULT);
    setImportLinks("tq_l1", ImportStates.DEFAULT);
    setImportLinks("tq_l2", ImportStates.DEFAULT);
    setImportLinks("tw_l1", ImportStates.DEFAULT);
    setImportLinks("tw_l2", ImportStates.DEFAULT);

    $(".import.l2_import").hide();
    $(".import.l3_import").hide();
    $(".import.tn_l1_import").show();
    $(".import.tn_l2_import").hide();
    $(".import.tq_l1_import").show();
    $(".import.tq_l2_import").hide();
    $(".import.tw_l1_import").show();
    $(".import.tw_l2_import").hide();

    $("#eventAction").val("create");
    $("button[name=startEvent]").text(Language.create);
}

function uploadImageContent(image, editor) {
    var data = new FormData();
    data.append("image", image);
    $.ajax({
        url: "/admin/rpc/upload_image",
        cache: false,
        contentType: false,
        processData: false,
        data: data,
        dataType: "json",
        type: "post",
        success: function(data) {
            console.log(data);
            if(data.success) {
                if(data.ext == "pdf") {
                    $(editor).summernote('createLink', {
                        text: "Set link name",
                        url: data.url,
                        isNewWindow: true
                    });
                } else {
                    var image = $("<img>").attr("src", data.url);
                    $(editor).summernote("insertNode", image[0]);
                }
            } else {
                renderPopup(data.error);
            }
        },
        error: function(data) {
            console.log(data);
        }
    });
}

function showProjectTools() {
    $(".toolsTn").removeClass("hidden");
    $(".toolsTq").removeClass("hidden");
    $(".toolsTw").removeClass("hidden");
    $(".toolsBc").removeClass("hidden");
}

function hideProjectTools() {
    $(".toolsTn").addClass("hidden");
    $(".toolsTq").addClass("hidden");
    $(".toolsTw").addClass("hidden");
    $(".toolsBc").addClass("hidden");
}
