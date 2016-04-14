fid = fopen('Dbelbin.csv','r');
l = fgetl(fid);
colnames = regexp(l,' ','split');
ncols = length(colnames);
Dbelbin = textscan(fid,repmat('%d',1,ncols),'delimiter',',','collectoutput',true);
fclose(fid);
Dbelbin = Dbelbin{1};

fid = fopen('Dpref.csv','r');
l = fgetl(fid);
studentnames = regexp(l,' ','split');
ncols = length(studentnames);
Dpref = textscan(fid,repmat('%d',1,ncols),'delimiter',',','collectoutput',true);
fclose(fid);
Dpref = Dpref{1};

Irnd = randperm(size(Dpref,1));
Dpref = Dpref(Irnd,Irnd);
Dbelbin = Dbelbin(Irnd,:);
studentnames = studentnames(Irnd);


nStudents = size(Dpref,1);
nClust = 3;
MaxMutation = 3;

%%%%%%%%%%%%%%%%%%

options = gaoptimset(@ga);
options = gaoptimset(options,...
    'PopulationSize',[30 30 30 30 30 30],...
    'EliteCount',ceil(30/100),...
    'CreationFcn',{@ClustStudCreate,nClust},...
    'CrossoverFcn',@ClustStudCrossover,...
    'MutationFcn',{@ClustStudMut,MaxMutation},...
    'PlotFcn',{@gaplotbestf,@gaplotscores},...
    'PlotInterval',3,...
    'StallGenLimit',500,...
    'TolFun',0,...
    'Generations',1000); 
    
IntCon = 1:nStudents;
nvars = nStudents;

[x,fval,exitflag,output] = ga({@ClustStudFit,Dpref,Dbelbin},nvars,[],[],[],[],...
    [],[],[],[],options);

[xs,Is] = sort(x);
studnames_sort = studentnames(Is);
close all
imagesc(Dpref(Is,Is))
set(gca,'xtick',1:length(x),'xticklabel',xs)
set(gca,'ytick',1:length(x),'yticklabel',studnames_sort)
figure();
imagesc(Dbelbin(Is,:))

