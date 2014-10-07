var Todo = Backbone.Model.extend({
    parse: function(data) {
        return data.response || data;
    }
});
var Todos = new (Backbone.Collection.extend({
    model: Todo,
    url: 'todos',
    parse: function(data) {
        return data.response
    }
}));
var TodosView = Backbone.View.extend({
    editing: {},
    collection: Todos,
    template: _.template('<ul><% for(var i in datas) { %><li><%= datas[i].title %> <button class="edit" data-id="<%= datas[i].id %>">E</button> <button class="remove" data-id="<%= datas[i].id %>">&times;</button></li><% } %></ul><input type="text" <% if(editing.id) {%> data-id="<%= editing.id %>" value="<%= editing.attributes.title %>" <%} %> placeholder="Enter to save" />'),
    render: _.debounce(function() {
        this.$el.html(this.template({
            editing: this.editing,
            datas: this.collection.toJSON()
        }));
        $('input', this.el).focus();
    }, 50),
    events: {
        'click .edit': function(e) {
            if(this.editing.id) {
                this.editing = {};
            } else {
                this.editing = this.collection.get($(e.currentTarget).data('id'));
            }
            this.render();
        },
        'click .remove': function(e) {
            var id = $(e.currentTarget).data('id');
            if(id === this.editing.id)
                this.editing = {};
            this.collection.get(id).destroy();
        },
        'keyup input': function(e) {
            var target = $(e.currentTarget);
            var value = target.val().trim();
            if(e.keyCode === 13 && value) {
                target.attr('disable', true);
                var id = target.data('id');
                if(id) {
                    this.collection.get(id).set({
                        title: value
                    }).save();
                } else {
                    this.collection.create({
                        title: value
                    });
                }
            }
        }
    },
    initialize: function() {
        this.collection.on('all', function(){
            this.render();
        }.bind(this)).fetch();
    }
});

new TodosView({el: '#output'});
